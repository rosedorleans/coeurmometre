<?php

namespace Value;

Use Value\Value;
Use Tools\Database;
Use Slack\Slack;
Use PDO;

class ValueDatabase extends Database{

    /**
     * @return Value[] $result
     */
    public function getAllValue(){
        $query = $this->PDO->prepare("SELECT * FROM `value`");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_CLASS, Value::class);
    }
    /**
     * @param int $id
     * @return Value $O_BDC
     */
    public function getValue($id){
        $query = $this->PDO->prepare("SELECT * FROM `value`
                                      WHERE `id`=:id");
        $query->bindParam(':id', $id);
        $query->execute();
        return $query->fetchObject(Value::class);
    }

	// public function getDistinctCategory(){
	// 	$query = $this->PDO->prepare("SELECT DISTINCT `category`, `user`
	// 								  FROM `value`");
	// 	$query->execute();
	// 	return $query->fetchAll(Value::class);
	// }

    /**
	* @param Value $O_Value
	* @return Value
	*/
	public function createValue($O_value){
		$T_row = [];
		$T_insert = [];
		$T_value = [];

		if ($O_value->getId()) {
			$T_row[]="`id`";
			$T_insert[]=":id";
			$T_value[':id'] = $O_value->getId();
		}else{
			$T_row[]="`id`";
			$T_insert[]=":id";
			$T_value[':id'] = uniqid("", true);
        }
		if ($O_value->getValue()) {
			$T_row[]="`value`";
			$T_insert[]=":value";
			$T_value[':value'] = $O_value->getValue();
		}
        if ($O_value->getCategory()) {
			$T_row[]="`category`";
			$T_insert[]=":category";
			$T_value[':category'] = $O_value->getCategory();
		}
        if ($O_value->getDate()) {
			$T_row[]="`date`";
			$T_insert[]=":date";
			$T_value[':date'] = $O_value->getDate();
		}
        if ($O_value->getUser()) {
			$T_row[]="`user`";
			$T_insert[]=":user";
			$T_value[':user'] = $O_value->getUser();
		}

		$query = $this->PDO->prepare("INSERT INTO `value`(".implode(",",$T_row).") VALUES (".implode(",",$T_insert).")");
		$query->execute($T_value);
		return $this->getValue($O_value->getId());
    }

    /**
	* @param Value $O_Value
	* @return Value
	*/
	public function updateValue($O_value){
        $T_update = [];
		$T_value = [];
        
		if ($O_value->getValue()) {
            $T_update[]="`value` =:value";
			$T_value[':value'] = $O_value->getValue();
		}
		if ($O_value->getCategory()) {
            $T_update[]="`category` =:category";
			$T_value[':category'] = $O_value->getCategory();
		}
        if ($O_value->getDate()) {
            $T_update[]="`date` =:date";
			$T_value[':date'] = $O_value->getDate();
		}
        if ($O_value->getUser()) {
            $T_update[]="`user` =:user";
			$T_value[':user'] = $O_value->getUser();
		}

        $query = $this->PDO->prepare("UPDATE `value` SET ".implode(",",$T_update)." WHERE `id` = :id");
        $T_value[':id']= $O_value->getId();
        $query->execute($T_value);
        return $this->getValue($O_value->getId());
    }
}