<?php

require_once("api.php");
require_once("database.php");

/**
 * myAPI class
 *
 * The class that actually implements the REST API. 
 *
 * @author Nikos Kirtsis <nkirtsis@gmail.com>
 * @copyright 2015 Nikos Kirtsis
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */
class myAPI extends API{

	private $database;

	const AXE_DAMAGE = 500;
	const MAGIC_SPELL_DAMAGE = 500;
	const HEALING_POTION = 500;		

	public function __construct($db) {
		$this->database = $db;	
		parent::__construct();
		if ($this->entity != 'creatures') {
			$this->response('Method Not Allowed', 405);			
		}		
	}
	
	public function processRequest() {	
		if ($this->action != '') {
			if ( $this->method == 'PUT' && $this->action == 'hitWithAxe' ) {
				$this->hitWithAxe($this->id);
			} else if ( $this->method == 'PUT' && $this->action == 'performMagicSpell' ) {
				$this->performMagicSpell($this->id);
			} else if ( $this->method == 'PUT' && $this->action == 'drinkHealingPotion') {
				$this->drinkHealingPotion($this->id);
			}
		} else if ($this->id != '') {
			if ( $this->method == 'GET' ) {
				$this->getCreature($this->id);
			} else if ( $this->method == 'PUT' ) {
				$this->updateCreature($this->id, $this->input);
			} else if ( $this->method == 'DELETE' ) {
				$this->deleteCreature($this->id);
			}
		} else {		
			if ( $this->method == 'GET' ) {
				$this->getAllCreatures( $this->filters );
			} else if ( $this->method == 'POST' ) {
				$this->createCreature($this->input);
			} 
		}
	}

	private function hitWithAxe($id) {		
		$health = $this->database->getFieldValue($id, 'health');
		$type = $this->database->getFieldValue($id, 'creature_type');				
		// axe makes double damage to zombies
		if ($type == 'zombie') {			
			$newHealthValue = $health - 2*self::AXE_DAMAGE;
		} else {
			$newHealthValue = $health - self::AXE_DAMAGE;
		}						
		$newHealthValue = $newHealthValue > 0 ? $newHealthValue : 0;				
		$this->updateCreature($id, array('health'=>$newHealthValue));
	}

	private function performMagicSpell($id) {
		$health = $this->database->getFieldValue($id, 'health');
		$type = $this->database->getFieldValue($id, 'creature_type');
		// vampires are more vulnerable to magic
		if ($type != 'vampire') {			
			$newHealthValue = $health - rand( 0,  self::MAGIC_SPELL_DAMAGE);
		} else {
			$newHealthValue = $health - rand(self::MAGIC_SPELL_DAMAGE, 2*self::MAGIC_SPELL_DAMAGE);
		}		
		$newHealthValue = $newHealthValue > 0 ? $newHealthValue : 0;				
		$this->updateCreature($id, array('health'=>$newHealthValue));
	}

	private function drinkHealingPotion($id) {
		$health = $this->database->getFieldValue($id, 'health');
		$type = $this->database->getFieldValue($id, 'creature_type');
		// healing potion does not affect vampires and zombies
		if ($type != 'vampire' && $type != 'zombie') {			
			$newHealthValue = $health + self::HEALING_POTION;
		}		
		$newHealthValue = $newHealthValue > 0 ? $newHealthValue : 0;				
		$this->updateCreature($id, array('health'=>$newHealthValue));
	}	
	
	private function getCreature($id) {				
		$sql = $this->database->selectStatement('creatures', array('creature_id'=>$id));
		$result = $this->database->query($sql);
		$rows = $this->database->fetch($result);							
		$this->database->disconnect();				
		$this->response($rows, 200);		
	}
	
	private function updateCreature($id, $attrs) {		
		$sql = $this->database->updateStatement('creatures', $attrs, $id);
		$result = $this->database->query($sql);
		$this->database->disconnect();
		if ( $result === TRUE ) {
			$this->response('Record updated successfully', 200);
		} else {
			$this->response('Error updating record', 500);			
		}				
	}
	
	private function deleteCreature($id) {		
		$sql = $this->database->deleteStatement('creatures', $id);
		$result = $this->database->query($sql);									
		$this->database->disconnect();
		if ( $result === TRUE ) {
			$this->response('Record deleted successfully', 200);
		} else {
			$this->response('Error deleting record', 500);
		}				
	}
	
	private function createCreature($attrs) {		
		$sql = $this->database->insertStatement('creatures', $attrs);
		$result = $this->database->query($sql);									
		$this->database->disconnect();
		if ( $result === TRUE ) {
			$this->response('New record created successfully', 200);
		} else {			
			$this->response('Error creating record', 500);
		}				
	}
	
	private function getAllCreatures($filters) {		
		$sql = $this->database->selectStatement('creatures', $filters);
		$result = $this->database->query($sql);
		$rows = $this->database->fetch($result);							
		$this->database->disconnect();				
		$this->response($rows, 200);		
	}
	
}

$db = new database();
$api = new myAPI($db);
$api->processRequest();

?>