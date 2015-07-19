--TEST--
Test continue stmt
--DESCRIPTION--
Lowlevel basic test
--FILE--
<?php

require __DIR__ . '/../../../../Bootstrap.php';

use PhpToZephir\EngineFactory;
use PhpToZephir\Logger;
use Symfony\Component\Console\Output\NullOutput;
use PhpToZephir\Render\StringRender;
use PhpToZephir\CodeCollector\StringCodeCollector;

$engine = EngineFactory::getInstance(new Logger(new NullOutput(), false));
$fileOne   = <<<'EOT'
<?php

namespace Code\StrongType\ReturnStmt;

interface MyInterface
{
    /**
     * @return string
     */
	public function test($toto);
}
EOT;

$fileTwo   = <<<'EOT'
<?php

namespace Code\StrongType\ReturnStmt;

class DefinedInInterfaceReturn implements MyInterface
{
    public function test($toto)
    {
        return $toto;
    }
}
EOT;

$render = new StringRender();
$codeValidator = new PhpToZephir\CodeValidator();

foreach ($engine->convert(new StringCodeCollector(array($fileOne, $fileTwo))) as $file) {
	$zephir = $render->render($file);
	$codeValidator->isValid($zephir);
	
	echo $zephir . "\n;
}

?>
--EXPECT--
namespace Code\StrongType\ReturnStmt;

interface MyInterface
{
    /**
     * @return string
     */
    public function test(toto) -> string;

}
namespace Code\StrongType\ReturnStmt;

class DefinedInInterfaceReturn implements MyInterface
{
    public function test(toto)
    {
        
        return toto;
    }

}