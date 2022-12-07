
<?php
 
 final class Log_attack_Handler extends Generic_handler {
  
    protected $sql_properties = "log_attack.json";

      /**
    */
   public function __construct($db) {
      parent::__construct($db);
   }


	/**
	 * @param mixed $statement
	 * @param mixed $obj
	 * @return mixed
	 */
	protected function setInsertParams($stmt, Log_attack $obj) {
      $attack_given_malus = $obj->getAttack_given_malus();
      $attack_type = $obj->getAttack_type();
      $attacker_max_pa = $obj->getAttacker_max_pa();
      $attacker_side = $obj->getAttacker_side();
      $attacker_turn = $obj->getAttacker_turn();
      $attacker_type = $obj->getAttacker_type();
      $attacker_xp = $obj->getAttacker_xp();
      $attack_datetime = $obj->getAttack_datetime();
      $building_bonus_precision_attacker = $obj->getBuilding_bonus_precision_attacker();
      $building_bonus_precision_target = $obj->getBuilding_bonus_precision_target();
      $building_type_attacker = $obj->getBuilding_type_attacker();
      $building_type_target = $obj->getBuilding_type_target();
      $damage = $obj->getDamage();
      $damage_bonus = $obj->getDamage_bonus();
      $damage_weapon = $obj->getDamage_weapon();
      $distance_attack = $obj->getDistance_attack();
      $effective_precision = $obj->getEffective_precision();
      $id_attacker = $obj->getId_attacker();
      $id_attacker_unit_type = $obj->getId_attacker_unit_type();
      $id_building_attacker = $obj->getId_building_attacker();
      $id_building_target = $obj->getId_building_target();
      $id_target = $obj->getId_target();
      $id_target_unit_type = $obj->getId_target_unit_type();
      $id_tile_attack = $obj->getId_tile_attack();
      $id_tile_target = $obj->getId_tile_target();
      $id_weapon = $obj->getId_weapon();
      $nb_collat = $obj->getNb_collat();
      $pc_earn = $obj->getPc_earn();
      $protection = $obj->getProtection();
      $pv_attacker = $obj->getPv_attacker();
      $pv_target = $obj->getPv_target();
      $target_defense = $obj->getTarget_defense();
      $target_died = $obj->getTarget_died();
      $target_side = $obj->getTarget_side();
      $target_type = $obj->getTarget_type();
      $tile_bonus_distance_attack = $obj->getTile_bonus_distance_attack();
      $tile_ground_bonus_precision_attacker = $obj->getTile_ground_bonus_precision_attacker();
      $tile_ground_bonus_precision_target = $obj->getTile_ground_bonus_precision_target();
      $tile_ground_type_attacker = $obj->getTile_ground_type_attacker();
      $tile_ground_type_target = $obj->getTile_ground_type_target();
      $tile_malus_distance_target = $obj->getTile_malus_distance_target();
      $weapon_max_distance = $obj->getWeapon_max_distance();
      $weapon_min_distance = $obj->getWeapon_min_distance();
      $weapon_name = $obj->getWeapon_name();
      $weapon_precision = $obj->getWeapon_precision();
      $xp_earn = $obj->getXp_earn();
      
      $stmt->bindParam(":attack_given_malus", $attack_given_malus);
      $stmt->bindParam(":attack_type", $attack_type);
      $stmt->bindParam(":attacker_max_pa", $attacker_max_pa);
      $stmt->bindParam(":attacker_side", $attacker_side);
      $stmt->bindParam(":attacker_turn", $attacker_turn);
      $stmt->bindParam(":attacker_type", $attacker_type);
      $stmt->bindParam(":attacker_xp", $attacker_xp);
      $stmt->bindParam(":attack_datetime", $attack_datetime);
      $stmt->bindParam(":building_bonus_precision_attacker", $building_bonus_precision_attacker);
      $stmt->bindParam(":building_bonus_precision_target", $building_bonus_precision_target);
      $stmt->bindParam(":building_type_attacker", $building_type_attacker);
      $stmt->bindParam(":building_type_target", $building_type_target);
      $stmt->bindParam(":damage", $damage);
      $stmt->bindParam(":damage_bonus", $damage_bonus);
      $stmt->bindParam(":damage_weapon", $damage_weapon);
      $stmt->bindParam(":distance_attack", $distance_attack);
      $stmt->bindParam(":effective_precision", $effective_precision);
      $stmt->bindParam(":id_attacker", $id_attacker);
      $stmt->bindParam(":id_attacker_unit_type", $id_attacker_unit_type);
      $stmt->bindParam(":id_building_attacker", $id_building_attacker);
      $stmt->bindParam(":id_building_target", $id_building_target);
      $stmt->bindParam(":id_target", $id_target);
      $stmt->bindParam(":id_target_unit_type", $id_target_unit_type);
      $stmt->bindParam(":id_tile_attack", $id_tile_attack);
      $stmt->bindParam(":id_tile_target", $id_tile_target);
      $stmt->bindParam(":id_weapon", $id_weapon);
      $stmt->bindParam(":nb_collat", $nb_collat);
      $stmt->bindParam(":pc_earn", $pc_earn);
      $stmt->bindParam(":protection", $protection);
      $stmt->bindParam(":pv_attacker", $pv_attacker);
      $stmt->bindParam(":pv_target", $pv_target);
      $stmt->bindParam(":target_defense", $target_defense);
      $stmt->bindParam(":target_died", $target_died);
      $stmt->bindParam(":target_side", $target_side);
      $stmt->bindParam(":target_type", $target_type);
      $stmt->bindParam(":tile_bonus_distance_attack", $tile_bonus_distance_attack);
      $stmt->bindParam(":tile_ground_bonus_precision_attacker", $tile_ground_bonus_precision_attacker);
      $stmt->bindParam(":tile_ground_bonus_precision_target", $tile_ground_bonus_precision_target);
      $stmt->bindParam(":tile_ground_type_attacker", $tile_ground_type_attacker);
      $stmt->bindParam(":tile_ground_type_target", $tile_ground_type_target);
      $stmt->bindParam(":tile_malus_distance_target", $tile_malus_distance_target);
      $stmt->bindParam(":weapon_max_distance", $weapon_max_distance);
      $stmt->bindParam(":weapon_min_distance", $weapon_min_distance);
      $stmt->bindParam(":weapon_name", $weapon_name);
      $stmt->bindParam(":weapon_precision", $weapon_precision);
      $stmt->bindParam(":xp_earn", $xp_earn);
	}
   
	
	/**
	 *
	 * @param mixed $statement
	 * @param mixed $obj
	 * @return mixed
	 */
	protected function setUpdateParams($statement, $obj) {
	}
	
	/**
	 *
	 * @param mixed $statement
	 * @return mixed
	 */
	protected function mapper($statement) {
	}
	
	/**
	 *
	 * @param mixed $statement
	 * @return mixed
	 */
	protected function mapperAll($statement) {
	}

 
}
  

 ?>