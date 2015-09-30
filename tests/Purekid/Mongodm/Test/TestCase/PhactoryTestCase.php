<?php

namespace Purekid\Mongodm\Test\TestCase;

use Phactory\Mongo\Phactory;
use Purekid\Mongodm\MongoDB;

/**
 * Test Case Base Class for using Phactory *
 */
abstract class PhactoryTestCase extends \PHPUnit_Framework_TestCase
{
  protected static $db;
  protected static $phactory;

  public static function setUpBeforeClass()
  {
    MongoDB::setConfigBlock('testing', array(
      'connection' => array(
        'hostnames' => 'localhost',
        'database'  => 'test_db'
      )
    ));

    self::$db = MongoDB::instance('testing');
    self::$db->connect();

    if (!self::$phactory) {
      if (!self::$db->getDB() instanceof \MongoDB) {
        throw new \Exception('Could not connect to MongoDB');
      }
      
      self::$phactory = new Phactory(self::$db->getDB());
      self::$phactory->reset();
    }

    //set up Phactory db connection
    self::$phactory->reset();
  }

  public static function tearDownAfterClass()
  {
    foreach (self::$db->getDB()->getCollectionNames() as $collection) {
      self::$db->getDB()->$collection->drop();
    }
  }

  protected function setUp()
  {
  }

  protected function tearDown()
  {
    self::$phactory->recall();
  }
}
