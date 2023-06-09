<?php

namespace Value;

Use Value\Value;
Use Tools\Database;
Use Slack\Slack;
Use PDO;

class ValueDatabase extends Database{

	/**
	* @param int|null $category
	* @param int|null $user
	* @return Value[]
	*/
	public function getAllValue($category, $user){
		$query = "SELECT * FROM `value`";
		
		if($category !== 'null' || $user !== 'null') {
			$req_category = "";
			$req_user = "";

			$query .= " WHERE ";

			if ($category !== 'null') {
				$req_category = "`category` = '$category'";
			}
			if ($user !== 'null') {
				$req_user .= "`user` = '$user'";
			}
			

			$query .=
				($req_category ? $req_category : "").
				(($req_category && $req_user) ? " AND " : "").
				($req_user ? $req_user : "");


		}
		// var_dump($query);
		$query = $this->PDO->prepare($query);

		if($category !== 'null' || $user !== 'null'){
			$query->bindParam(':category', $category);
			$query->bindParam(':user', $user);
		}

		$query->execute();
		// var_dump($query->errorInfo());
		// var_dump($query->debugDumpParams());
        return $query->fetchAll(PDO::FETCH_CLASS, Value::class);
	}

    // /**
    //  * @return Value[] $result
    //  */
    // public function getAllValue(){
    //     $query = $this->PDO->prepare("SELECT * FROM `value`");
    //     $query->execute();
    //     return $query->fetchAll(PDO::FETCH_CLASS, Value::class);
    // }

    /**
     * @param int $id
     * @return Value $O_value
     */
    public function getValue($id){
        $query = $this->PDO->prepare("SELECT * FROM `value`
                                      WHERE `id`=:id");
        $query->bindParam(':id', $id);
        $query->execute();
        return $query->fetchObject(Value::class);
    }
    /**
     * @return Value $O_value
     */
	public function getDistinctCategory(){
		$query = $this->PDO->prepare("SELECT DISTINCT `category`
									  FROM `value`");
		$query->execute();
		return $query->fetchAll(PDO::FETCH_CLASS, Value::class);
	}
    /**
     * @return Value $O_value
     */
	public function getDistinctUser(){
		$query = $this->PDO->prepare("SELECT DISTINCT `user`
									  FROM `value`");
		$query->execute();
		// var_dump($query->errorInfo());
		// var_dump($query->debugDumpParams());
        return $query->fetchAll(PDO::FETCH_CLASS, Value::class);
	}

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
		var_dump($query);
		$query->execute($T_value);
		var_dump($query->errorInfo());
		var_dump($query->debugDumpParams());
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
		} else {
			$T_update[]="`user`=:user";
			$T_value[':user']= NULL;
		}

        $query = $this->PDO->prepare("UPDATE `value` SET ".implode(",",$T_update)." WHERE `id` = :id");
        $T_value[':id']= $O_value->getId();
        $query->execute($T_value);
		// var_dump($query->errorInfo());
		// var_dump($query->debugDumpParams());
        return $this->getValue($O_value->getId());
    }
}