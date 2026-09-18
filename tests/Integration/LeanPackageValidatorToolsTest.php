<?php

namespace Stolt\LeanPackageValidatorMcp\Tests\Integration;

use PHPUnit\Framework\TestCase;
use Stolt\LeanPackageValidatorMcp\LeanPackageValidatorTools;

class LeanPackageValidatorToolsTest extends TestCase
{
    private string $tempDir;
    private LeanPackageValidatorTools $tools;

    protected function setUp(): void
    {
        $this->tools = new LeanPackageValidatorTools();
        
        $this->tempDir = sys_get_temp_dir() . '/lpv_mcp_test_' . uniqid();
        mkdir($this->tempDir);
        
        // Initialize a dummy git repository for commands that require it
        exec("git init {$this->tempDir}");
        exec("git config --global user.email 'test@example.com' || true");
        exec("git config --global user.name 'Test User' || true");
        file_put_contents($this->tempDir . '/dummy.txt', 'dummy content');
        mkdir($this->tempDir . '/tests');
        file_put_contents($this->tempDir . '/tests/DummyTest.php', 'dummy test');
        file_put_contents($this->tempDir . '/phpunit.xml', '<phpunit/>');
        mkdir($this->tempDir . '/docs');
        file_put_contents($this->tempDir . '/docs/dummy.md', 'dummy docs');
        exec("cd {$this->tempDir} && git add . && git commit -m 'Initial commit'");
    }

    protected function tearDown(): void
    {
        if (is_dir($this->tempDir)) {
            exec("rm -rf {$this->tempDir}");
        }
    }

    public function testLeanPackageValidatorMcpListCommand(): void
    {
        $output = $this->tools->leanPackageValidatorMcp(['list']);
        
        $this->assertStringContainsString('Available commands:', $output);
        $this->assertStringContainsString('validate', $output);
        $this->assertStringContainsString('create', $output);
    }

    public function testGenerateGitattributes(): void
    {
        $output = $this->tools->generateGitattributes(
            directory: $this->tempDir,
            preset: 'Php'
        );

        $this->assertStringContainsString('has been created', $output);
        
        $gitattributesPath = $this->tempDir . '/.gitattributes';
        $this->assertFileExists($gitattributesPath);
        
        $content = file_get_contents($gitattributesPath);
        $this->assertStringContainsString('tests/ export-ignore', $content);
        $this->assertStringContainsString('phpunit.xml export-ignore', $content);
    }

    public function testUpdateGitattributes(): void
    {
        // First, create a dummy .gitattributes file in the repo
        $gitattributesContent = <<<'EOD'
docs/ export-ignore
dummy.txt export-ignore
EOD;
        file_put_contents($this->tempDir . '/.gitattributes', $gitattributesContent);
        
        // Update it using the tool
        $output = $this->tools->updateGitattributes(
            directory: $this->tempDir,
            preset: 'Php'
        );

        $this->assertStringContainsString('has been updated', $output);

        $updatedContent = file_get_contents($this->tempDir . '/.gitattributes');
        // Check that the new PHP ignores were merged in
        $this->assertStringContainsString('phpunit.xml export-ignore', $updatedContent);
        // And the old ones were kept
        $this->assertStringContainsString('docs/ export-ignore', $updatedContent);
    }

    public function testValidatePackageArchive(): void
    {
        // Create an initial .gitattributes using the tool
        $this->tools->generateGitattributes(
            directory: $this->tempDir,
            preset: 'Php'
        );

        // Run validate
        $output = $this->tools->validatePackageArchive(
            directory: $this->tempDir,
            validateGitArchive: false // Skip the actual git archive matching for a simpler test
        );

        // In a bare repository with the default generated PHP .gitattributes,
        // it might report some files that do not exist (stale) or pass successfully.
        $this->assertStringContainsString('is considered valid', $output);
    }
}
