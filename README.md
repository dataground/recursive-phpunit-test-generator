# PHPUnit test generator
Recursively create PhpUnit test skeletons by convention for a composer project using PSR autoloading and naming.
It will generate skeleton tests for all classes in the source folder recursively.
It will skip interfaces and existing tests. Target folders will be created based on source folders.

## Usage
    php bin/testcreator /my/project /my/project/tests/unit
    
### Example class (src/My/NameSpace/Foo.php)
    
    namespace My\NameSpace;
    
    class Foo {
    
        public function bar() {
            ... some code
        }
   
    }
    
### Resulting test (test/unit/My/NameSpace/FooTest.php)
       
    namespace My\NameSpace;
    
    /*
    @TODO Public API to cover:
    @covers My\NameSpace\Foo::bar()
    */
    
    class FooTest extends TestCase {
    
        /**
         * @type My\NameSpace\Foo;
         */
        private $foo = null;
    
        /**
         *
         */
        protected function setUp():void
        {
            parent::setUp();
            // $this->foo = new Foo( ... );
        }
    
        /**
         *
         */
        protected function tearDown():void
        {
            // $this->foo = null;
            parent::tearDown();
        }
    
        /**
         *
         */
        public function testTodo()
        {
            $this->markTestSkipped('Tests not implemented yet for Foo');
        }
    
    }

## TODO
* Put logic in classes
* Add tests (duh..)  