<?php

namespace App\Command;

use App\Entity\Card;
use App\Entity\SpellEffect;
use App\Enum\Card\CardType;
use App\Enum\SpellEffect\EffectType;
use App\Enum\SpellEffect\TargetMode;
use App\Enum\SpellEffect\TargetRule;
use App\Enum\SpellEffect\TargetSide;
use App\Enum\SpellEffect\TargetType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:card:import', description: 'Import cards from a CSV file')]
class ImportCardsCommand extends Command
{
    private const VALID_COLORS = ['Red', 'Green', 'Blue'];

    public function __construct(private readonly EntityManagerInterface $em)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('file', InputArgument::REQUIRED, 'Path to the CSV file')
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Simulate import without saving');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io     = new SymfonyStyle($input, $output);
        $file   = $input->getArgument('file');
        $dryRun = $input->getOption('dry-run');

        if (!file_exists($file)) {
            $io->error("File not found: $file");
            return Command::FAILURE;
        }

        $handle = fopen($file, 'r');
        if (!$handle) {
            $io->error("Cannot open file: $file");
            return Command::FAILURE;
        }

        $header = fgetcsv($handle);
        if ($header === false) {
            $io->error('Empty CSV file.');
            return Command::FAILURE;
        }

        $imported = 0;
        $skipped  = 0;
        $errors   = [];
        $row      = 1;

        // Colonnes attendues :
        // name,type,color,attack,hp,heal,description,targetType,targetSide,targetMode,targetRule,effectType,value,duration
        while (($data = fgetcsv($handle)) !== false) {
            $row++;

            if (count($data) < 4) {
                $errors[] = "Row $row: not enough columns.";
                $skipped++;
                continue;
            }

            [
                $name, $typeRaw, $color, $attack, $hp, $heal, $description,
                $targetTypeRaw, $targetSideRaw, $targetModeRaw, $targetRuleRaw,
                $effectTypeRaw, $value, $duration,
            ] = array_pad($data, 14, null);

            if (empty($name)) {
                $errors[] = "Row $row: name is required.";
                $skipped++;
                continue;
            }

            $type = CardType::tryFrom($typeRaw ?? '');
            if (null === $type) {
                $errors[] = "Row $row: invalid type \"$typeRaw\". Must be one of: " . implode(', ', CardType::values());
                $skipped++;
                continue;
            }

            if (!in_array($color, self::VALID_COLORS, true)) {
                $errors[] = "Row $row: invalid color \"$color\". Must be one of: " . implode(', ', self::VALID_COLORS);
                $skipped++;
                continue;
            }

            $spellEffect = null;
            if ($type->isSpell()) {
                $targetType = TargetType::tryFrom($targetTypeRaw ?? '');
                $targetSide = TargetSide::tryFrom($targetSideRaw ?? '');
                $targetMode = TargetMode::tryFrom($targetModeRaw ?? '');
                $effectType = EffectType::tryFrom($effectTypeRaw ?? '');
                $targetRule = $targetRuleRaw ? TargetRule::tryFrom($targetRuleRaw) : null;

                if (null === $targetType) {
                    $errors[] = "Row $row: invalid targetType \"$targetTypeRaw\" for spell card.";
                    $skipped++;
                    continue;
                }
                if (null === $targetSide) {
                    $errors[] = "Row $row: invalid targetSide \"$targetSideRaw\" for spell card.";
                    $skipped++;
                    continue;
                }
                if (null === $targetMode) {
                    $errors[] = "Row $row: invalid targetMode \"$targetModeRaw\" for spell card.";
                    $skipped++;
                    continue;
                }
                if (null === $effectType) {
                    $errors[] = "Row $row: invalid effectType \"$effectTypeRaw\" for spell card.";
                    $skipped++;
                    continue;
                }
                if ($targetMode === TargetMode::AUTO && null === $targetRule) {
                    $errors[] = "Row $row: targetRule is required when targetMode is auto.";
                    $skipped++;
                    continue;
                }
                if ('' === $value || null === $value) {
                    $errors[] = "Row $row: value is required for spell card.";
                    $skipped++;
                    continue;
                }

                if (!$dryRun) {
                    $spellEffect = new SpellEffect();
                    $spellEffect->setTargetType($targetType);
                    $spellEffect->setTargetSide($targetSide);
                    $spellEffect->setTargetMode($targetMode);
                    $spellEffect->setTargetRule($targetRule);
                    $spellEffect->setEffectType($effectType);
                    $spellEffect->setValue((int) $value);
                    $spellEffect->setDuration($duration !== '' && $duration !== null ? (int) $duration : null);
                }
            }

            if (!$dryRun) {
                $card = new Card();
                $card->setName(trim($name));
                $card->setType($type);
                $card->setColor($color);
                $card->setAttack($attack !== '' && $attack !== null ? (int) $attack : null);
                $card->setHp($hp !== '' && $hp !== null ? (int) $hp : null);
                $card->setHeal($heal !== '' && $heal !== null ? (int) $heal : null);
                $card->setDescription($description !== '' && $description !== null ? trim($description) : null);

                if ($spellEffect !== null) {
                    $card->setSpellEffect($spellEffect);
                }

                $this->em->persist($card);
            }

            $imported++;
        }

        fclose($handle);

        if (!$dryRun) {
            $this->em->flush();
        }

        if ($errors) {
            $io->warning('Some rows were skipped:');
            foreach ($errors as $error) {
                $io->text("  • $error");
            }
        }

        $status = $dryRun ? '[DRY RUN] ' : '';
        $io->success("{$status}{$imported} card(s) imported, {$skipped} skipped.");

        return Command::SUCCESS;
    }
}