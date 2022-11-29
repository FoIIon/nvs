<?php

class Log_attack implements \JsonSerializable{

    //attacker
    private $id_attacker;
    private $attacker_type;
    private $attacker_unit_type;
    private $attack_type;//cac - dist - charge
	private $attacker_turn;
    private $pc_earn;
    private $xp_earn;
    private $effective_precision; //chance de touche réelle au moment de l'attaque
    private $id_case_attack;
    private $case_ground_type_attacker;
    private $case_ground_bonus_precision_attacker; //bonus can also be a malus if negative
    private $distance_attack;
    private $case_bonus_distance_attack; //bonus can also be a malus if negative
    private $pv_attacker;
    private $attacker_side;
    private $id_building_attacker; //id du batiment dans lequel se trouve le joueur
    private $building_bonus_precision_attacker; //bonus can also be a malus if negative
    private $building_type_attacker; //fortin, ps, train, ...

    //target
    private $id_target;
    private $target_type;
    private $target_unit_type;
    private $protection;
    private $id_case_target;
    private $case_ground_type_target;
    private $case_ground_bonus_precision_target; //bonus can also be a malus if negative
    private $case_malus_distance_target; //est-ce que la case de la cible donne un malus de distance à l'attaquant ?
    private $pv_target; //before damage calculation
    private $target_defense; //before attack, without bonus/malus calculation
    private $target_side;
    private $id_building_target; //id du batiment dans lequel se trouve la cible
    private $building_bonus_precision_target; //bonus can also be a malus if negative
    private $building_type_target; //fortin, ps, train, ...


    //weapon
    private $id_weapon;
    private $weapon_name;
    private $damage_weapon;//ex:20d8
    private $damage; //damage roll without any deduction
    private $damage_bonus; //bonus charge par ex
    private $weapon_precision;//% de touche de l'arme
    private $weapon_max_distance;
    private $weapon_min_distance;
    
    //generale info
    private $attck_datetime;
    
    
    public function toString() :self{
        $class_vars = get_class_vars(get_class($this));

        foreach ($class_vars as $name => $value) {
            echo get_class($this).' : '. $name .' : ' . $value .' <br/>';
        }
    }

	function console_log($with_script_tags = true) {
		$js_code = 'console.log(' . json_encode($this) . 
	');';
		if ($with_script_tags) {
			$js_code = '<script>' . $js_code . '</script>';
		}
		echo $js_code;
	}

	public function jsonSerialize()
    {
        return (object) get_object_vars($this);
    }

	/**
	 * @return mixed
	 */
	public function getWeapon_min_distance() {
		return $this->weapon_min_distance;
	}
	
	/**
	 * @param mixed $weapon_min_distance 
	 * @return self
	 */
	public function setWeapon_min_distance($weapon_min_distance): self {
		$this->weapon_min_distance = $weapon_min_distance;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getPc_earn() {
		return $this->pc_earn;
	}
	
	/**
	 * @param mixed $pc_earn 
	 * @return self
	 */
	public function setPc_earn($pc_earn): self {
		$this->pc_earn = $pc_earn;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getBuilding_type_attacker() {
		return $this->building_type_attacker;
	}
	
	/**
	 * @param mixed $building_type_attacker 
	 * @return self
	 */
	public function setBuilding_type_attacker($building_type_attacker): self {
		$this->building_type_attacker = $building_type_attacker;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getId_attacker() {
		return $this->id_attacker;
	}
	
	/**
	 * @param mixed $id_attacker 
	 * @return self
	 */
	public function setId_attacker($id_attacker): self {
		$this->id_attacker = $id_attacker;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getAttacker_type() {
		return $this->attacker_type;
	}
	
	/**
	 * @param mixed $attacker_type 
	 * @return self
	 */
	public function setAttacker_type($attacker_type): self {
		$this->attacker_type = $attacker_type;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getAttacker_unit_type() {
		return $this->attacker_unit_type;
	}
	
	/**
	 * @param mixed $attacker_unit_type 
	 * @return self
	 */
	public function setAttacker_unit_type($attacker_unit_type): self {
		$this->attacker_unit_type = $attacker_unit_type;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getAttack_type() {
		return $this->attack_type;
	}
	
	/**
	 * @param mixed $attack_type 
	 * @return self
	 */
	public function setAttack_type($attack_type): self {
		$this->attack_type = $attack_type;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getXp_earn() {
		return $this->xp_earn;
	}
	
	/**
	 * @param mixed $xp_earn 
	 * @return self
	 */
	public function setXp_earn($xp_earn): self {
		$this->xp_earn = $xp_earn;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getEffective_precision() {
		return $this->effective_precision;
	}
	
	/**
	 * @param mixed $effective_precision 
	 * @return self
	 */
	public function setEffective_precision($effective_precision): self {
		$this->effective_precision = $effective_precision;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getId_case_attack() {
		return $this->id_case_attack;
	}
	
	/**
	 * @param mixed $id_case_attack 
	 * @return self
	 */
	public function setId_case_attack($id_case_attack): self {
		$this->id_case_attack = $id_case_attack;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getCase_ground_type_attacker() {
		return $this->case_ground_type_attacker;
	}
	
	/**
	 * @param mixed $case_ground_type_attacker 
	 * @return self
	 */
	public function setCase_ground_type_attacker($case_ground_type_attacker): self {
		$this->case_ground_type_attacker = $case_ground_type_attacker;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getCase_ground_bonus_precision_attacker() {
		return $this->case_ground_bonus_precision_attacker;
	}
	
	/**
	 * @param mixed $case_ground_bonus_precision_attacker 
	 * @return self
	 */
	public function setCase_ground_bonus_precision_attacker($case_ground_bonus_precision_attacker): self {
		$this->case_ground_bonus_precision_attacker = $case_ground_bonus_precision_attacker;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getDistance_attack() {
		return $this->distance_attack;
	}
	
	/**
	 * @param mixed $distance_attack 
	 * @return self
	 */
	public function setDistance_attack($distance_attack): self {
		$this->distance_attack = $distance_attack;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getCase_bonus_distance_attack() {
		return $this->case_bonus_distance_attack;
	}
	
	/**
	 * @param mixed $case_bonus_distance_attack 
	 * @return self
	 */
	public function setCase_bonus_distance_attack($case_bonus_distance_attack): self {
		$this->case_bonus_distance_attack = $case_bonus_distance_attack;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getPv_attacker() {
		return $this->pv_attacker;
	}
	
	/**
	 * @param mixed $pv_attacker 
	 * @return self
	 */
	public function setPv_attacker($pv_attacker): self {
		$this->pv_attacker = $pv_attacker;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getAttacker_side() {
		return $this->attacker_side;
	}
	
	/**
	 * @param mixed $attacker_side 
	 * @return self
	 */
	public function setAttacker_side($attacker_side): self {
		$this->attacker_side = $attacker_side;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getId_building_attacker() {
		return $this->id_building_attacker;
	}
	
	/**
	 * @param mixed $id_building_attacker 
	 * @return self
	 */
	public function setId_building_attacker($id_building_attacker): self {
		$this->id_building_attacker = $id_building_attacker;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getBuilding_bonus_precision_attacker() {
		return $this->building_bonus_precision_attacker;
	}
	
	/**
	 * @param mixed $building_bonus_precision_attacker 
	 * @return self
	 */
	public function setBuilding_bonus_precision_attacker($building_bonus_precision_attacker): self {
		$this->building_bonus_precision_attacker = $building_bonus_precision_attacker;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getId_target() {
		return $this->id_target;
	}
	
	/**
	 * @param mixed $id_target 
	 * @return self
	 */
	public function setId_target($id_target): self {
		$this->id_target = $id_target;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getTarget_type() {
		return $this->target_type;
	}
	
	/**
	 * @param mixed $target_type 
	 * @return self
	 */
	public function setTarget_type($target_type): self {
		$this->target_type = $target_type;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getTarget_unit_type() {
		return $this->target_unit_type;
	}
	
	/**
	 * @param mixed $target_unit_type 
	 * @return self
	 */
	public function setTarget_unit_type($target_unit_type): self {
		$this->target_unit_type = $target_unit_type;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getProtection() {
		return $this->protection;
	}
	
	/**
	 * @param mixed $protection 
	 * @return self
	 */
	public function setProtection($protection): self {
		$this->protection = $protection;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getId_case_target() {
		return $this->id_case_target;
	}
	
	/**
	 * @param mixed $id_case_target 
	 * @return self
	 */
	public function setId_case_target($id_case_target): self {
		$this->id_case_target = $id_case_target;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getCase_ground_type_target() {
		return $this->case_ground_type_target;
	}
	
	/**
	 * @param mixed $case_ground_type_target 
	 * @return self
	 */
	public function setCase_ground_type_target($case_ground_type_target): self {
		$this->case_ground_type_target = $case_ground_type_target;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getCase_ground_bonus_precision_target() {
		return $this->case_ground_bonus_precision_target;
	}
	
	/**
	 * @param mixed $case_ground_bonus_precision_target 
	 * @return self
	 */
	public function setCase_ground_bonus_precision_target($case_ground_bonus_precision_target): self {
		$this->case_ground_bonus_precision_target = $case_ground_bonus_precision_target;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getCase_malus_distance_target() {
		return $this->case_malus_distance_target;
	}
	
	/**
	 * @param mixed $case_malus_distance_target 
	 * @return self
	 */
	public function setCase_malus_distance_target($case_malus_distance_target): self {
		$this->case_malus_distance_target = $case_malus_distance_target;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getPv_target() {
		return $this->pv_target;
	}
	
	/**
	 * @param mixed $pv_target 
	 * @return self
	 */
	public function setPv_target($pv_target): self {
		$this->pv_target = $pv_target;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getTarget_defense() {
		return $this->target_defense;
	}
	
	/**
	 * @param mixed $target_defense 
	 * @return self
	 */
	public function setTarget_defense($target_defense): self {
		$this->target_defense = $target_defense;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getTarget_side() {
		return $this->target_side;
	}
	
	/**
	 * @param mixed $target_side 
	 * @return self
	 */
	public function setTarget_side($target_side): self {
		$this->target_side = $target_side;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getId_building_target() {
		return $this->id_building_target;
	}
	
	/**
	 * @param mixed $id_building_target 
	 * @return self
	 */
	public function setId_building_target($id_building_target): self {
		$this->id_building_target = $id_building_target;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getBuilding_bonus_precision_target() {
		return $this->building_bonus_precision_target;
	}
	
	/**
	 * @param mixed $building_bonus_precision_target 
	 * @return self
	 */
	public function setBuilding_bonus_precision_target($building_bonus_precision_target): self {
		$this->building_bonus_precision_target = $building_bonus_precision_target;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getBuilding_type_target() {
		return $this->building_type_target;
	}
	
	/**
	 * @param mixed $building_type_target 
	 * @return self
	 */
	public function setBuilding_type_target($building_type_target): self {
		$this->building_type_target = $building_type_target;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getId_weapon() {
		return $this->id_weapon;
	}
	
	/**
	 * @param mixed $id_weapon 
	 * @return self
	 */
	public function setId_weapon($id_weapon): self {
		$this->id_weapon = $id_weapon;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getWeapon_name() {
		return $this->weapon_name;
	}
	
	/**
	 * @param mixed $weapon_name 
	 * @return self
	 */
	public function setWeapon_name($weapon_name): self {
		$this->weapon_name = $weapon_name;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getDamage_weapon() {
		return $this->damage_weapon;
	}
	
	/**
	 * @param mixed $damage_weapon 
	 * @return self
	 */
	public function setDamage_weapon($damage_weapon): self {
		$this->damage_weapon = $damage_weapon;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getDamage() {
		return $this->damage;
	}
	
	/**
	 * @param mixed $damage 
	 * @return self
	 */
	public function setDamage($damage): self {
		$this->damage = $damage;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getDamage_bonus() {
		return $this->damage_bonus;
	}
	
	/**
	 * @param mixed $damage_bonus 
	 * @return self
	 */
	public function setDamage_bonus($damage_bonus): self {
		$this->damage_bonus = $damage_bonus;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getWeapon_precision() {
		return $this->weapon_precision;
	}
	
	/**
	 * @param mixed $weapon_precision 
	 * @return self
	 */
	public function setWeapon_precision($weapon_precision): self {
		$this->weapon_precision = $weapon_precision;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getWeapon_max_distance() {
		return $this->weapon_max_distance;
	}
	
	/**
	 * @param mixed $weapon_max_distance 
	 * @return self
	 */
	public function setWeapon_max_distance($weapon_max_distance): self {
		$this->weapon_max_distance = $weapon_max_distance;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getAttck_datetime() {
		return $this->attck_datetime;
	}
	
	/**
	 * @param mixed $attck_datetime 
	 * @return self
	 */
	public function setAttck_datetime($attck_datetime): self {
		$this->attck_datetime = $attck_datetime;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getAttacker_turn() {
		return $this->attacker_turn;
	}
	
	/**
	 * @param mixed $attacker_turn 
	 * @return self
	 */
	public function setAttacker_turn($attacker_turn): self {
		$this->attacker_turn = $attacker_turn;
		return $this;
	}
}