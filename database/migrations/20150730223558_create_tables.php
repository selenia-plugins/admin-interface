<?php

use Phinx\Migration\AbstractMigration;

class CreateTables extends AbstractMigration
{
  /**
   * Change Method.
   *
   * Write your reversible migrations using this method.
   *
   * More information on writing migrations is available here:
   * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
   */
  public function change ()
  {
    $this
      ->table ('users')
      ->addColumn ('username', 'string', ['limit' => 30])
      ->addIndex (['username'], ['unique' => true])
      ->addColumn ('password', 'string', ['limit' => 60])
      ->addColumn ('token', 'string', ['limit' => 60, 'null' => true])
      ->addColumn ('registrationDate', 'datetime')
      ->addColumn ('lastLogin', 'datetime', ['null' => true])
      ->addColumn ('role', 'integer')
      ->addColumn ('active', 'boolean', ['default' => 0])
      ->create ();
    $now = date ('Y-m-d H:i:s');
    $this->execute ("
      INSERT INTO users (username, registrationDate, role, active)
      VALUES ('admin', '$now', 2, 1);
");

    $this
      ->table ('images', ['id' => false, 'primary_key' => ['id']])
      ->addColumn ('id', 'string', ['limit' => 13])
      ->addColumn ('ext', 'string', ['limit' => 4])
      ->addColumn ('from', 'string', ['limit' => 45, 'null' => true])
      ->addColumn ('key', 'string', ['limit' => 30, 'null' => true])
      ->addColumn ('caption', 'string', ['limit' => 255, 'null' => true])
      ->addColumn ('gallery', 'integer', ['null' => true])//TODO: drop this
      ->addColumn ('sort', 'integer', ['default' => 0])
      ->addIndex ('key')
      ->addIndex ('sort')
      ->create ();

    $this
      ->table ('files', ['id' => false, 'primary_key' => ['id']])
      ->addColumn ('id', 'string', ['limit' => 13])
      ->addColumn ('ext', 'string', ['limit' => 4])
      ->addColumn ('name', 'string', ['limit' => 255])
      ->addColumn ('from', 'string', ['limit' => 45, 'null' => true])
      ->create ();
/*
    $this
      ->table ('strings', ['id' => false, 'primary_key' => ['id', 'lang']])
      ->addColumn ('id', 'string', ['limit' => 13])
      ->addColumn ('lang', 'string', ['limit' => 5])
      ->addColumn ('from', 'string', ['limit' => 45, 'null' => true])
      ->addColumn ('text', 'string', ['limit' => 255])
      ->addIndex ('from')
      ->create ();

    $this
      ->table ('texts', ['id' => false, 'primary_key' => ['id', 'lang']])
      ->addColumn ('id', 'string', ['limit' => 13])
      ->addColumn ('lang', 'string', ['limit' => 5])
      ->addColumn ('from', 'string', ['limit' => 45, 'null' => true])
      ->addColumn ('text', 'text')
      ->addIndex ('from')
      ->create ();
*/
  }
}