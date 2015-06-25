<?php
class ModelSaleAir extends Model {
    
    public function addAir($data) {
        
                
                
                $this->db->query("INSERT INTO `" . DB_PREFIX . "air` SET 
                
                date_departure = '".$this->dateToSql($data['date_departure'])."',
                date_arrival = '".$this->dateToSql($data['date_arrival'])."',
                date_doc = '".$this->dateToSql($data['date_doc'])."',
                date_gruz = '".$this->dateToSql($data['date_gruz'])."',
                sandbox = '".$this->user->getSandbox()."',
                date_added = NOW(),
                air_status_id = 0");
                $air_id = $this->db->getLastId();
                if (!$data['name']) {
                    $data['name'] = "A". str_pad($air_id, 3, "0", STR_PAD_LEFT);
                }
                
                $this->db->query("UPDATE `" . DB_PREFIX . "air` SET `name` = '".$this->db->escape($data['name'])."' WHERE air_id = ".(int)$air_id);
                 
                //$data['status_id'] = $this->config->get('config_pack_status_3_id');
                $this->editAir($air_id, $data);
                return $air_id;
    }
    private function dateToSql($strDate) {
        list($date,$time) = explode(" ",$strDate);
        $date = implode("-",array_reverse(explode("-",$date)));
        if (isset($time) && $time) {
            return $date." ".$time;
        } else {
            return $date;
        }
    }    
    public function editair($air_id, $data) {
                    
                
                $set = array();
                if (isset($data['name'])) $set[] = "`name` = '".$this->db->escape($data['name'])."' ";
                if (isset($data['date_departure'])) $set[] = " date_departure = '".$this->dateToSql($data['date_departure'])."'";
                if (isset($data['date_arrival'])) $set[] = "date_arrival = '".$this->dateToSql($data['date_arrival'])."'";
                if (isset($data['date_doc'])) $set[] = "date_doc = '".$this->dateToSql($data['date_doc'])."'";
                if (isset($data['date_gruz'])) $set[] = "date_gruz = '".$this->dateToSql($data['date_gruz'])."'";
                
                if (count($set)) {
                    $sql = "UPDATE `" . DB_PREFIX . "air` SET ";
                    $sql .= implode(", ",$set);
                    $sql .= " WHERE air_id = ".(int)$air_id;
                    $this->db->query($sql);
                }
                
                
                
                if(!empty($data['parcel'])) {
                        
                        foreach($data['parcel'] as $parcel_id) {
                                $address_id = $this->getAddress($parcel_id); // 0 - значит нужно сгенерить случайный адрес
                                $sql = "INSERT INTO " . DB_PREFIX . "air_parcel SET ";
                                $sql .= "air_id = ".(int)$air_id.", ";
                                $sql .= "parcel_id = ".(int)$parcel_id.", ";
                                $sql .= "address_id = ".($address_id ? $address_id : $this->getRandAddressID($air_id));
                                $this->db->query($sql);
                        }
                }
                //Если добавлено часть посылок с другим статусом то обновим у них статус в функции  addHistory
                if (!isset($data['status_id'])) {
                    $q = $this->db->query("SELECT air_status_id FROM `" . DB_PREFIX . "air` WHERE air_id = ".(int)$air_id);
                    $data['status_id'] = $q->row['air_status_id'];
                } 
                $this->addHistory($air_id, $data['status_id'], $data);
    }
    public function getAddress($parcel_id){
        $q = $this->db->query("SELECT 
                ppp.parcel_id,p.pack_id,COUNT(DISTINCT p.pack_id) as pack_total,a.* 
                FROM `parcel_pack_product` ppp
                LEFT JOIN pack p ON (p.pack_id=ppp.pack_id)
                LEFT JOIN address a ON (a.customer_id = p.customer_id)
                WHERE p.customer_id<>0 AND ppp.parcel_id = ".(int)$parcel_id." GROUP BY ppp.parcel_id");
        if (!trim($q->row['city']) && !trim($q->row['address_1'])) {
            return 0;    
        } else {
            return $q->row['address_id'];    
        }
    }                 
                     
    public function getRandAddressID($air_id) {
        $q = $this->db->query("SELECT address_id FROM `".DB_PREFIX."address` WHERE customer_id = 0 AND sandbox = ".$this->user->getSandbox()."
        AND address_id NOT IN (SELECT address_id FROM air_parcel ap WHERE ap.air_id = ".$air_id." AND address_id<>0) 
        ORDER BY RAND() LIMIT 1");
        return $q->row['address_id'];
    }
	public function getAir($air_id,$parcels = true) {
        $sql = "SELECT * FROM air a
        WHERE a.air_id = '" . (int)$air_id . "'";
		$air_query = $this->db->query($sql);

		if ($air_query->num_rows) {
            if ($parcels) {
			    $air_parcels = $this->getAirParcels($air_id);
                $air_query->row['parcels_count'] = count($air_parcels);
                $air_query->row['parcels'] = $air_parcels;
            }
			return $air_query->row;
		} else {
			return;
		}
	}

	public function getAirs($data = array()) {
		$sql  = "SELECT a.*,
        (SELECT ps.name FROM " . DB_PREFIX . "pack_status ps WHERE ps.pack_status_id = a.air_status_id AND ps.language_id = '" . (int)$this->config->get('config_language_id') . "') AS air_status 
         FROM `" . DB_PREFIX . "air` a ";
		if (isset($data['filter_status_id'])) {
			$implode = array();

			$air_statuses = explode(',', $data['filter_status_id']);

			foreach ($air_statuses as $air_status_id) {
				$implode[] = "a.air_status_id = '" . (int)$air_status_id . "'";
			}

			if ($implode) {
				$sql .= " WHERE (" . implode(" OR ", $implode) . ")";
			} else {

			}
		} else {
			$sql .= " WHERE a.air_status_id > '0'";
		}

                
		if (!empty($data['filter_air_id'])) {
			$sql .= " AND a.air_id = '" . (int)$data['filter_air_id'] . "'";
		}
                
		if (!empty($data['filter_name'])) {
			$sql .= " AND a.name LIKE '" . $this->db->escape($data['filter_name']) . "%')";
		}
                
		if (!empty($data['filter_air_ids'])) {
			$sql .= " AND a.air_id IN ('" . implode("', '", $data['filter_air_ids']) . "')";
		}



		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(a.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

	
        $sql .= " AND a.sandbox = ".$this->user->getSandbox();
        
		$sort_data = array(
			'a.air_id',
			'status',
			'a.date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY a.air_id";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);
                

		return $query->rows;
	}

	public function getAirParcels($air_id) {
		$query = $this->db->query("SELECT 
        ap.*,
        a.*,
        p.*,
        (SELECT ps.name FROM " . DB_PREFIX . "pack_status ps WHERE ps.pack_status_id = p.parcel_status_id AND ps.language_id = '" . (int)$this->config->get('config_language_id') . "') AS parcel_status
         FROM " . DB_PREFIX . "air_parcel ap 
        LEFT JOIN `parcel` p ON (p.parcel_id=ap.parcel_id)
        LEFT JOIN `address` a ON (a.address_id=ap.address_id)
        WHERE ap.air_id = '" . (int)$air_id . "' ORDER BY ap.parcel_id");
		return $query->rows;
	}

	public function getTotalairs($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "air` a ";
		if (isset($data['filter_status_id'])) {
			$implode = array();
			$air_statuses = explode(',', $data['filter_status_id']);
			foreach ($air_statuses as $air_status_id) {
				$implode[] = "a.air_status_id = '" . (int)$air_status_id . "'";
			}
			if ($implode) {
				$sql .= " WHERE (" . implode(" OR ", $implode) . ")";
			} else {

			}
		} else {
			$sql .= " WHERE a.air_status_id > '0'";
		}

		if (!empty($data['filter_air_id'])) {
			$sql .= " AND a.air_id = '" . (int)$data['filter_air_id'] . "'";
		}

		if (!empty($data['filter_air_ids'])) {
			$sql .= " AND a.air_id IN ('" . implode("', '", $data['filter_air_ids']) . "')";
		}

        if (!empty($data['filter_name'])) {
            $sql .= " AND a.name LIKE '" . $this->db->escape($data['filter_name']) . "%')";
        }

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(a.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

        $sql .= " AND a.sandbox = ".$this->user->getSandbox();

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
        
        public function deleteair($air_id, $tables = array('air','air_parcel','air_history')) {
                
                foreach($tables as $table) {
                        $this->db->query("DELETE FROM `" . DB_PREFIX . $table . "` WHERE air_id = '" . (int)$air_id . "' AND sandbox = ".$this->user->getSandbox());
                }
                return true;
        }

        /*
	public function getairHistories($air_id, $start = 0, $limit = 10) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}

		$query = $this->db->query("SELECT ph.*, ps.name AS air_status, u.username as user "
                        . "FROM " . DB_PREFIX . "air_history ph "
                        . "LEFT JOIN " . DB_PREFIX . "air_status ps ON ph.air_status_id = ps.air_status_id AND ps.language_id = '" . (int)$this->config->get('config_language_id') . "' "
                        . "LEFT JOIN user u ON u.user_id = ph.user_id "
                        . "WHERE ph.air_id = '" . (int)$air_id . "' "
                        . ""
                        . "ORDER BY ph.date_added ASC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	public function getTotalairHistories($air_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "air_history WHERE air_id = '" . (int)$air_id . "'");

		return $query->row['total'];
	}            */
        
      
        
        public function addHistory($air_id, $air_status_id, $data) {
                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "air_history WHERE air_id = '" . (int)$air_id . "' AND air_status_id = '" . (int)$air_status_id . "' LIMIT 1");
                
                if($query->num_rows) {
                        $use = 0;
                } else {
                        $use = 1;
                }
             
                $this->load->model('localisation/pack_status');
                $pack_status_info = $this->model_localisation_pack_status->getPackStatus($air_status_id);
                
                if(!empty($pack_status_info['sms_text'])) {
                                $this->load->model('sale/parcel');
                                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "air_parcel WHERE air_id = '" . $air_id . "' GROUP BY parcel_id");
                                $tmp = $data;
                                $tmp['from_air'] = true;
                                
                                foreach($query->rows as $row) {
                                        $this->model_sale_parcel->addHistory($row['parcel_id'], $air_status_id, $tmp);
                                }
                        
                } 
                
                //Добавляем историю только если еще неыбло данного статуса
                if ($use) {
                     $sql  = "INSERT INTO " . DB_PREFIX . "air_history SET "
                             . "date_added = NOW(), "
                             . "air_id = '" . (int)$air_id . "', "
                             . "air_status_id = '" . (int)$air_status_id . "' ";
                             
                     $this->db->query($sql);
                     
                     $sql  = "UPDATE " . DB_PREFIX . "air SET "
                             . "air_status_id = '" . (int)$air_status_id . "' "
                             . "WHERE air_id = '" . (int)$air_id . "'";
                     $this->db->query($sql);
                }
        }

}