<?php

namespace Stolt\LeanPackageValidatorMcp;

use PhpMcp\Server\Attributes\McpTool;
use Symfony\Component\Process\Process;

class LeanPackageValidatorTools
{
    private string $binPath;

    public function __construct()
    {
        $this->binPath = __DIR__ . '/../vendor/bin/lean-package-validator';
    }

    private function runCommand(array $command): string
    {
        $process = new Process($command);
        $process->run();

        if (!$process->isSuccessful()) {
            return "Error: " . $process->getErrorOutput() . "\n" . $process->getOutput();
        }

        return $process->getOutput() ?: "Success";
    }

    #[McpTool(
        name: 'lean_package_validator_mcp',
        description: 'Run arbitrary lean-package-validator commands.'
    )]
    public function leanPackageValidatorMcp(array $args): string
    {
        return $this->runCommand(array_merge([$this->binPath], $args));
    }

    #[McpTool(
        name: 'generate_gitattributes',
        description: 'Create a new .gitattributes file for a project/micro-package repository.'
    )]
    public function generateGitattributes(
        string $directory = '.',
        ?string $preset = null,
        ?string $globPattern = null,
        bool $keepLicense = false,
        bool $keepReadme = false,
        bool $alignExportIgnores = false,
        bool $sortFromDirectoriesToFiles = false,
        bool $dryRun = false
    ): string {
        $cmd = [$this->binPath, 'create'];
        
        if ($preset !== null) {
            $cmd[] = '--preset=' . $preset;
        }
        if ($globPattern !== null) {
            $cmd[] = '--glob-pattern=' . $globPattern;
        }
        if ($keepLicense) {
            $cmd[] = '--keep-license';
        }
        if ($keepReadme) {
            $cmd[] = '--keep-readme';
        }
        if ($alignExportIgnores) {
            $cmd[] = '--align-export-ignores';
        }
        if ($sortFromDirectoriesToFiles) {
            $cmd[] = '--sort-from-directories-to-files';
        }
        if ($dryRun) {
            $cmd[] = '--dry-run';
        }

        $cmd[] = $directory;

        return $this->runCommand($cmd);
    }

    #[McpTool(
        name: 'update_gitattributes',
        description: 'Update an existing .gitattributes file for a project/micro-package repository.'
    )]
    public function updateGitattributes(
        string $directory = '.',
        bool $reformatExportIgnores = false,
        bool $group = false,
        ?string $preset = null,
        ?string $globPattern = null,
        bool $alignExportIgnores = false,
        bool $sortFromDirectoriesToFiles = false,
        bool $dryRun = false
    ): string {
        $cmd = [$this->binPath, 'update'];

        if ($reformatExportIgnores) {
            $cmd[] = '--reformat-export-ignores';
        }
        if ($group) {
            $cmd[] = '--group';
        }
        if ($preset !== null) {
            $cmd[] = '--preset=' . $preset;
        }
        if ($globPattern !== null) {
            $cmd[] = '--glob-pattern=' . $globPattern;
        }
        if ($alignExportIgnores) {
            $cmd[] = '--align-export-ignores';
        }
        if ($sortFromDirectoriesToFiles) {
            $cmd[] = '--sort-from-directories-to-files';
        }
        if ($dryRun) {
            $cmd[] = '--dry-run';
        }

        $cmd[] = $directory;

        return $this->runCommand($cmd);
    }

    #[McpTool(
        name: 'validate_package_archive',
        description: 'Validate the .gitattributes file of a given project/micro-package repository, including Git archive validation.'
    )]
    public function validatePackageArchive(
        string $directory = '.',
        bool $validateGitArchive = true,
        bool $enforceStrictOrder = false,
        bool $enforceAlignment = false,
        ?string $preset = null,
        bool $keepLicense = false,
        bool $keepReadme = false
    ): string {
        $cmd = [$this->binPath, 'validate'];

        if ($validateGitArchive) {
            $cmd[] = '--validate-git-archive';
        }
        if ($enforceStrictOrder) {
            $cmd[] = '--enforce-strict-order';
        }
        if ($enforceAlignment) {
            $cmd[] = '--enforce-alignment';
        }
        if ($preset !== null) {
            $cmd[] = '--preset=' . $preset;
        }
        if ($keepLicense) {
            $cmd[] = '--keep-license';
        }
        if ($keepReadme) {
            $cmd[] = '--keep-readme';
        }

        $cmd[] = $directory;

        return $this->runCommand($cmd);
    }
}
