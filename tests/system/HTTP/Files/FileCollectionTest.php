<?php
namespace CodeIgniter\HTTP\Files;

class FileCollectionTest extends \CIUnitTestCase
{

	public function setUp()
	{
		parent::setUp();
		$_FILES = [];
	}

	//--------------------------------------------------------------------

	public function testAllReturnsArrayWithNoFiles()
	{
		$files = new FileCollection();

		$this->assertEquals([], $files->all());
	}

	//--------------------------------------------------------------------

	public function testAllReturnsValidSingleFile()
	{
		$_FILES = [
			'userfile' => [
				'name'		 => 'someFile.txt',
				'type'		 => 'text/plain',
				'size'		 => '124',
				'tmp_name'	 => '/tmp/myTempFile.txt',
				'error'		 => 0
			]
		];

		$collection = new FileCollection();
		$files = $collection->all();
		$this->assertCount(1, $files);

		$file = array_shift($files);
		$this->assertInstanceOf(UploadedFile::class, $file);

		$this->assertEquals('someFile.txt', $file->getName());
		$this->assertEquals(124, $file->getSize());
	}

	//--------------------------------------------------------------------

	public function testAllReturnsValidMultipleFilesSameName()
	{
		$_FILES = [
			'userfile' => [
				'name'		 => ['fileA.txt', 'fileB.txt'],
				'type'		 => ['text/plain', 'text/csv'],
				'size'		 => ['124', '248'],
				'tmp_name'	 => ['/tmp/fileA.txt', '/tmp/fileB.txt'],
				'error'		 => 0
			]
		];

		$collection = new FileCollection();
		$files = $collection->all();
		$this->assertCount(1, $files);
		$this->assertEquals('userfile', key($files));

		$files = array_shift($files);
		$this->assertCount(2, $files);

		$file = $files[0];
		$this->assertInstanceOf(UploadedFile::class, $file);

		$this->assertEquals('fileA.txt', $file->getName());
		$this->assertEquals('/tmp/fileA.txt', $file->getTempName());
		$this->assertEquals('txt', $file->getClientExtension());
		$this->assertEquals('text/plain', $file->getClientMimeType());
		$this->assertEquals(124, $file->getSize());
	}

	//--------------------------------------------------------------------


	public function testAllReturnsValidMultipleFilesDifferentName()
	{
		$_FILES = [
			'userfile1'	 => [
				'name'		 => 'fileA.txt',
				'type'		 => 'text/plain',
				'size'		 => 124,
				'tmp_name'	 => '/tmp/fileA.txt',
				'error'		 => 0
			],
			'userfile2'	 => [
				'name'		 => 'fileB.txt',
				'type'		 => 'text/csv',
				'size'		 => 248,
				'tmp_name'	 => '/tmp/fileB.txt',
				'error'		 => 0
			],
		];

		$collection = new FileCollection();
		$files = $collection->all();
		$this->assertCount(2, $files);
		$this->assertEquals('userfile1', key($files));

		$file = array_shift($files);
		$this->assertInstanceOf(UploadedFile::class, $file);

		$this->assertEquals('fileA.txt', $file->getName());
		$this->assertEquals('fileA.txt', $file->getClientName());
		$this->assertEquals('/tmp/fileA.txt', $file->getTempName());
		$this->assertEquals('txt', $file->getClientExtension());
		$this->assertEquals('text/plain', $file->getClientMimeType());
		$this->assertEquals(124, $file->getSize());

		$file = array_pop($files);
		$this->assertInstanceOf(UploadedFile::class, $file);

		$this->assertEquals('fileB.txt', $file->getName());
		$this->assertEquals('fileB.txt', $file->getClientName());
		$this->assertEquals('/tmp/fileB.txt', $file->getTempName());
		$this->assertEquals('txt', $file->getClientExtension());
		$this->assertEquals('text/csv', $file->getClientMimeType());
		$this->assertEquals(248, $file->getSize());
	}

	//--------------------------------------------------------------------

	/**
	 * @group single
	 */
	public function testAllReturnsValidSingleFileNestedName()
	{
		$_FILES = [
			'userfile' => [
				'name'		 => [
					'foo' => [
						'bar' => 'fileA.txt'
					]
				],
				'type'		 => [
					'foo' => [
						'bar' => 'text/plain'
					]
				],
				'size'		 => [
					'foo' => [
						'bar' => 124
					]
				],
				'tmp_name'	 => [
					'foo' => [
						'bar' => '/tmp/fileA.txt'
					]
				],
				'error'		 => 0
			]
		];

		$collection = new FileCollection();
		$files = $collection->all();
		$this->assertCount(1, $files);
		$this->assertEquals('userfile', key($files));

		$this->assertArrayHasKey('bar', $files['userfile']['foo']);

		$file = $files['userfile']['foo']['bar'];
		$this->assertInstanceOf(UploadedFile::class, $file);

		$this->assertEquals('fileA.txt', $file->getName());
		$this->assertEquals('/tmp/fileA.txt', $file->getTempName());
		$this->assertEquals('txt', $file->getClientExtension());
		$this->assertEquals('text/plain', $file->getClientMimeType());
		$this->assertEquals(124, $file->getSize());
	}

	//--------------------------------------------------------------------

	public function testHasFileWithSingleFile()
	{
		$_FILES = [
			'userfile' => [
				'name'		 => 'someFile.txt',
				'type'		 => 'text/plain',
				'size'		 => '124',
				'tmp_name'	 => '/tmp/myTempFile.txt',
				'error'		 => 0
			]
		];

		$collection = new FileCollection();

		$this->assertTrue($collection->hasFile('userfile'));
		$this->assertFalse($collection->hasFile('foo'));
	}

	//--------------------------------------------------------------------

	public function testHasFileWithMultipleFilesWithDifferentNames()
	{
		$_FILES = [
			'userfile1'	 => [
				'name'		 => 'fileA.txt',
				'type'		 => 'text/plain',
				'size'		 => 124,
				'tmp_name'	 => '/tmp/fileA.txt',
				'error'		 => 0
			],
			'userfile2'	 => [
				'name'		 => 'fileB.txt',
				'type'		 => 'text/csv',
				'size'		 => 248,
				'tmp_name'	 => '/tmp/fileB.txt',
				'error'		 => 0
			],
		];

		$collection = new FileCollection();

		$this->assertTrue($collection->hasFile('userfile1'));
		$this->assertTrue($collection->hasFile('userfile2'));
	}

	//--------------------------------------------------------------------

	/**
	 * @group single
	 */
	public function testHasFileWithSingleFileNestedName()
	{
		$_FILES = [
			'userfile' => [
				'name'		 => [
					'foo' => [
						'bar' => 'fileA.txt'
					]
				],
				'type'		 => [
					'foo' => [
						'bar' => 'text/plain'
					]
				],
				'size'		 => [
					'foo' => [
						'bar' => 124
					]
				],
				'tmp_name'	 => [
					'foo' => [
						'bar' => '/tmp/fileA.txt'
					]
				],
				'error'		 => 0
			]
		];

		$collection = new FileCollection();

		$this->assertTrue($collection->hasFile('userfile'));
		$this->assertTrue($collection->hasFile('userfile.foo'));
		$this->assertTrue($collection->hasFile('userfile.foo.bar'));
	}

	//--------------------------------------------------------------------

	public function testErrorString()
	{
		$_FILES = [
			'userfile' => [
				'name'		 => 'someFile.txt',
				'type'		 => 'text/plain',
				'size'		 => '124',
				'tmp_name'	 => '/tmp/myTempFile.txt',
				'error'		 => UPLOAD_ERR_INI_SIZE
			]
		];

		$expected = 'The file "someFile.txt" exceeds your upload_max_filesize ini directive.';

		$collection = new FileCollection();
		$file = $collection->getFile('userfile');

		$this->assertEquals($expected, $file->getErrorString());
	}

	public function testErrorStringWithUnknownError()
	{
		$_FILES = [
			'userfile' => [
				'name'		 => 'someFile.txt',
				'type'		 => 'text/plain',
				'size'		 => '124',
				'tmp_name'	 => '/tmp/myTempFile.txt',
				'error'		 => 123
			]
		];

		$expected = 'The file "someFile.txt" was not uploaded due to an unknown error.';

		$collection = new FileCollection();
		$file = $collection->getFile('userfile');

		$this->assertEquals($expected, $file->getErrorString());
	}

	public function testErrorStringWithNoError()
	{
		$_FILES = [
			'userfile' => [
				'name'		 => 'someFile.txt',
				'type'		 => 'text/plain',
				'size'		 => '124',
				'tmp_name'	 => '/tmp/myTempFile.txt',
			]
		];

		$expected = 'The file uploaded with success.';

		$collection = new FileCollection();
		$file = $collection->getFile('userfile');

		$this->assertEquals($expected, $file->getErrorString());
	}

	//--------------------------------------------------------------------

	public function testError()
	{
		$_FILES = [
			'userfile' => [
				'name'		 => 'someFile.txt',
				'type'		 => 'text/plain',
				'size'		 => '124',
				'tmp_name'	 => '/tmp/myTempFile.txt',
				'error'		 => UPLOAD_ERR_INI_SIZE
			]
		];

		$collection = new FileCollection();
		$file = $collection->getFile('userfile');

		$this->assertEquals(UPLOAD_ERR_INI_SIZE, $file->getError());
	}

	public function testErrorWithUnknownError()
	{
		$_FILES = [
			'userfile' => [
				'name'		 => 'someFile.txt',
				'type'		 => 'text/plain',
				'size'		 => '124',
				'tmp_name'	 => '/tmp/myTempFile.txt',
			]
		];

		$collection = new FileCollection();
		$file = $collection->getFile('userfile');

		$this->assertEquals(0, $file->getError());
	}

	public function testErrorWithNoError()
	{
		$_FILES = [
			'userfile' => [
				'name'		 => 'someFile.txt',
				'type'		 => 'text/plain',
				'size'		 => '124',
				'tmp_name'	 => '/tmp/myTempFile.txt',
				'error'		 => 0
			]
		];

		$collection = new FileCollection();
		$file = $collection->getFile('userfile');

		$this->assertEquals(UPLOAD_ERR_OK, $file->getError());
	}

	//--------------------------------------------------------------------

	public function testFileReturnsValidSingleFile()
	{
		$_FILES = [
			'userfile' => [
				'name'		 => 'someFile.txt',
				'type'		 => 'text/plain',
				'size'		 => '124',
				'tmp_name'	 => '/tmp/myTempFile.txt',
				'error'		 => 0
			]
		];

		$collection = new FileCollection();
		$file = $collection->getFile('userfile');
		$this->assertInstanceOf(UploadedFile::class, $file);

		$this->assertEquals('someFile.txt', $file->getName());
		$this->assertEquals(124, $file->getSize());
	}

	//--------------------------------------------------------------------

	public function testFileNoExistSingleFile()
	{
		$_FILES = [
			'userfile' => [
				'name'		 => 'someFile.txt',
				'type'		 => 'text/plain',
				'size'		 => '124',
				'tmp_name'	 => '/tmp/myTempFile.txt',
				'error'		 => 0
			]
		];

		$collection = new FileCollection();
		$file = $collection->getFile('fileuser');
		$this->AssertNull($file);
	}

	//--------------------------------------------------------------------

	public function testFileReturnValidMultipleFiles()
	{
		$_FILES = [
			'userfile' => [
				'name'		 => ['fileA.txt', 'fileB.txt'],
				'type'		 => ['text/plain', 'text/csv'],
				'size'		 => ['124', '248'],
				'tmp_name'	 => ['/tmp/fileA.txt', '/tmp/fileB.txt'],
				'error'		 => 0
			]
		];

		$collection = new FileCollection();

		$file_1 = $collection->getFile('userfile.0');
		$this->assertInstanceOf(UploadedFile::class, $file_1);
		$this->assertEquals('fileA.txt', $file_1->getName());
		$this->assertEquals('/tmp/fileA.txt', $file_1->getTempName());
		$this->assertEquals('txt', $file_1->getClientExtension());
		$this->assertEquals('text/plain', $file_1->getClientMimeType());
		$this->assertEquals(124, $file_1->getSize());

		$file_2 = $collection->getFile('userfile.1');
		$this->assertInstanceOf(UploadedFile::class, $file_2);
		$this->assertEquals('fileB.txt', $file_2->getName());
		$this->assertEquals('/tmp/fileB.txt', $file_2->getTempName());
		$this->assertEquals('txt', $file_2->getClientExtension());
		$this->assertEquals('text/csv', $file_2->getClientMimeType());
		$this->assertEquals(248, $file_2->getSize());
	}

	//--------------------------------------------------------------------

	public function testFileWithMultipleFilesNestedName()
	{
		$_FILES = [
			'my-form' => [
				'name'		 => [
					'details' => [
						'avatars' => ['fileA.txt', 'fileB.txt']
					]
				],
				'type'		 => [
					'details' => [
						'avatars' => ['text/plain', 'text/plain']
					]
				],
				'size'		 => [
					'details' => [
						'avatars' => [125, 243]
					]
				],
				'tmp_name'	 => [
					'details' => [
						'avatars' => ['/tmp/fileA.txt', '/tmp/fileB.txt']
					]
				],
				'error'		 => [
					'details' => [
						'avatars' => [0, 0]
					]
				],
			]
		];

		$collection = new FileCollection();

		$file_1 = $collection->getFile('my-form.details.avatars.0');
		$this->assertInstanceOf(UploadedFile::class, $file_1);
		$this->assertEquals('fileA.txt', $file_1->getName());
		$this->assertEquals('/tmp/fileA.txt', $file_1->getTempName());
		$this->assertEquals('txt', $file_1->getClientExtension());
		$this->assertEquals('text/plain', $file_1->getClientMimeType());
		$this->assertEquals(125, $file_1->getSize());

		$file_2 = $collection->getFile('my-form.details.avatars.1');
		$this->assertInstanceOf(UploadedFile::class, $file_2);
		$this->assertEquals('fileB.txt', $file_2->getName());
		$this->assertEquals('/tmp/fileB.txt', $file_2->getTempName());
		$this->assertEquals('txt', $file_2->getClientExtension());
		$this->assertEquals('text/plain', $file_2->getClientMimeType());
		$this->assertEquals(243, $file_2->getSize());
	}

	//--------------------------------------------------------------------
}
