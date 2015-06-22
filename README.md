# PHPUnit test generator
Recursively create PhpUnit test skeletons by convention for a composer project using PSR autoloading and naming.
It will generate skeleton tests in the `tests/unit` folder for all classes in the `src` folder recursively.
It will skip interfaces and existing tests. Target folders will be created based on source folders.

## Usage
    php testcreator.php [Composer project root]
    
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
    bar()
    */
    
    class FooTest {
    
        /**
         * @type My\NameSpace\Foo;
         */
        private $foo = null;
    
        /**
         *
         */
        protected function setUp()
        {
            parent::setUp();
            // $this->foo = new Foo( ... );
        }
    
        /**
         *
         */
        protected function tearDown()
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

    