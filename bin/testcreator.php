<?php
/**
 *  PHPUnit test generator
 *  Creates tests for all classes in the /src dir
 *  Uses standard convention for the naming
 *  Will skip existing classes
 */

echo 'TEST GENERATOR v0.2'.PHP_EOL;
echo 'By Dataground 2015 (c)'.PHP_EOL;

if (!isset($argv[1])) {
    die('Usage '.__FILE__.' [project directory] [output directory (optional)]'.PHP_EOL);
}

$baseDir = rtrim($argv[1],'/');

if (isset($argv[2]) || trim($argv[2]) !== '') {
    $destDir = rtrim($argv[2],'/');
}
else {
    $destDir = $baseDir.'/tests';
}

$sourceDir = $baseDir.'/src';

if (!is_file($baseDir.'/composer.json')) {
    die($baseDir.'/composer.json not found');
}

if (!is_file($baseDir.'/vendor/autoload.php')) {
    die($baseDir.'/vendor/autoload.php not found');
}

if (!is_dir($sourceDir)) {
    die($sourceDir.' not found');
}

if (!is_dir($destDir)) {
    die($destDir.' not found');
}

// Load composer autoloader of target project
require $baseDir.'/vendor/autoload.php';

$directory = new RecursiveDirectoryIterator($sourceDir);
$iterator = new RecursiveIteratorIterator($directory);

$files = array();
foreach ($iterator as $info) {
    $path = $info->getPathName();
    $pathParts = explode($sourceDir, $path);
    $files[] = $pathParts[1];
}

// Iterate all classes
foreach ($files as $file) {

    $fileParts = explode('/', $file);

    if (is_file($sourceDir . $file) && substr($file, -4) === '.php') {

        echo $file.PHP_EOL;

        $fileName = str_replace('.php', 'Test.php', array_pop($fileParts));
        $filePath = trim(join('/', $fileParts), '/');
        $testName = $destDir . '/' . $filePath . '/' . $fileName;

        if (!file_exists($testName)) {
            $className = str_replace(array('/', '.php'), array('\\', ''), $file);
            $class = new ReflectionClass($className);

            // Skip interfaces
            if ($class->isInterface() === FALSE) {

                if (!is_dir($destDir . '/' . $filePath)) {
                    mkdir($destDir . '/' . $filePath, 0755, true);
                }
                else {
                    echo 'Directory exists: ' . $destDir . '/' . $filePath . PHP_EOL;
                }

                $methods = $class->getMethods(ReflectionMethod::IS_PUBLIC);

                $methodComments = array();

                foreach ($methods as $method) {
                    $methodComments[] = '// @covers ' . $method->getDeclaringClass()->getName() . '::'. $method->name;
                }

                $template = file_get_contents(__DIR__ . '/../templates/test.phpt');

                $tags = array(
                    'xnamespacex' => str_replace(array('/', '.php'), array('\\', ''), $filePath),
                    'xclassfqdnx' => trim(str_replace(array('/', '.php'), array('\\', ''), $file), '\\'),
                    'xclassnamex' => str_replace(array('.php'), array(''), $fileName),
                    'xsourceclassnamex' => str_replace('Test', '', str_replace(array('.php'), array(''), $fileName)),
                    'xsourceclassnamelowerx' => str_replace(
                        'Test',
                        '',
                        lcfirst(str_replace(array('.php'), array(''), $fileName))
                    ),
                    'xcommentx' => join(PHP_EOL, $methodComments),
                    'xdatetimex' => date('Y-m-d H:i:s')
                );

                echo 'Generating empty test: ' . $testName . PHP_EOL;
                file_put_contents($testName, str_replace(array_keys($tags), array_values($tags), $template));

            } else {
                echo 'Skipping interface ' .$className . PHP_EOL;
            }
        }
        else {
            echo 'Skipping existing test ' . $testName . PHP_EOL;
        }
    }
}