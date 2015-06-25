<?php
class ModelSaleParcel extends Model {
    
        public function addParcel($data) {
            //определяем префикс для посылки
           /* $q = $this->db->query("SELECT c.partner_id, p.pack_number FROM pack p
            LEFT JOIN ".DB_PREFIX."customer c ON (c.customer_id = p.customer_id) 
            WHERE p.pack_id = ".(int)$data['pack_id']." LIMIT 1");         */
            //$partner_id = $q->row['partner_id'];
//            if (!$partner_id) {
//                $parcel_number = $q->row['pack_number'];
//            } else {
//                $this->load->model('sale/pack');
//                $parcel_number = $this->model_sale_pack->getAutoincPackNubmer(0);
//            }

            $parcel_number = "";
            
            $packs = array();
            $packs_products = array();
            foreach ($data['parcel_pack_product'] as $products) {
                if (!in_array($products['pack_id'],$packs)) {
                    $packs[] = $products['pack_id'];
                }
                $packs_products[$products['pack_product_id']] = $products['quantity'];
            }
            if (count($packs)==1) {  // если переносим одну упаковку
               $q = $this->db->query("SELECT c.partner_id, p.pack_number FROM pack p
               LEFT JOIN ".DB_PREFIX."customer c ON (c.customer_id = p.customer_id) 
               WHERE p.pack_id = ".(int)$packs[0]." LIMIT 1");    
               $partner_id = $q->row['partner_id'];
               $pack_number = $q->row['pack_number'];
               if (!$partner_id) { //если это авиапочта, не дочерний
                    $q = $this->db->query("SELECT * FROM `parcel_pack_product` WHERE pack_id = ".(int)$packs[0]." LIMIT 1");
                    
                    if (!$q->num_rows) { //если не разбивали упаковку
                        
                        //Проверяем переносил лии  всю упаковку
                        $q = $this->db->query("SELECT * FROM `pack_product` WHERE pack_id = ".(int)$packs[0]."");
                        $full = true;
                        
                        foreach ($q->rows as $row) { 
                            if (!isset($packs_products[$row['pack_product_id']])) {
                                $full = false;
                                break;   
                            } else if ($packs_products[$row['pack_product_id']]!=$row['quantity']) {
                                $full = false;
                                break;
                            }
                        }
                        if ($full) {
                            $parcel_number = $pack_number;
                        }
                    }
               }
            }
            
            if (!$parcel_number) {
                $this->load->model('sale/pack');
                $parcel_number = $this->model_sale_pack->getAutoincPackNubmer();
            }
            
            ////
            $n = $this->getMaxPoint()+1;
            $this->db->query("INSERT INTO `" . DB_PREFIX . "parcel` SET 
            sandbox = ".$this->user->getSandbox().", 
            external_id  = ".$n.", 
            date_added = NOW(), 
            parcel_number = '".$parcel_number."'");
            $parcel_id = $this->db->getLastId();
            $this->editParcel($parcel_id, $data);
            $this->addHistory($parcel_id, $this->config->get('config_pack_status_2_id'), $data);
            return $parcel_id;
        }
        
        public function editParcel($parcel_id, $data) {
                $data['update_packs'] = 1;
                $this->_customeEditParcel($parcel_id, $data);
        }
    
    public function getMaxPoint() {
        $q = $this->db->query("SELECT air_id FROM air WHERE air_status_id = ".$this->config->get('config_pack_status_3_id')." AND date_doc>=NOW() AND sandbox = ".$this->user->getSandbox()." ORDER BY date_doc LIMIT 1");
        if ($q->num_rows) {
            $air_id = $q->row['air_id'];
            $q = $this->db->query("
            SELECT max(external_id) as m FROM `air_parcel` ap
            LEFT JOIN parcel p ON (p.parcel_id = ap.parcel_id)
            WHERE  air_id = ".$air_id."
            ");
            $amax = $q->row['m'];
        }  else {
            $amax = 0;
        }
        $q = $this->db->query("SELECT max( external_id ) as m FROM parcel WHERE parcel_status_id = ".(int)$this->config->get('config_pack_status_2_id')." AND sandbox = ".$this->user->getSandbox());
        $max = $q->row['m'];
        return max($amax,$max);
    }
	public function getParcel($parcel_id) {       
        
		$pack_query = $this->db->query("SELECT *, 
        (SELECT air_id FROM air_parcel ap WHERE ap.parcel_id = p.parcel_id LIMIT 1) as `use`,
        (SELECT ps.name FROM " . DB_PREFIX . "pack_status ps WHERE ps.pack_status_id = p.parcel_status_id AND ps.language_id = '" . (int)$this->config->get('config_language_id') . "') AS parcel_status FROM `" . DB_PREFIX . "parcel` p 
        WHERE p.parcel_id = '" . (int)$parcel_id . "' ");

		if ($pack_query->num_rows) {

			$parcel_packs = $this->getParcelPacks($parcel_id);
            $pack_query->row['pack_count'] = count($parcel_packs);
            $pack_query->row['parcel_packs'] = $parcel_packs;
            $pack_query->row['language_id']       = (int)$this->config->get('config_language_id');
            $pack_query->row['currency_id']       = (int)$this->config->get('config_currency_id');
            $pack_query->row['currency_code']     = $this->currency->getCode((int)$this->config->get('config_currency_id'));
            $pack_query->row['currency_value']    = $this->currency->getValue($this->currency->getCode((int)$this->config->get('config_currency_id')));
                                
			return $pack_query->row;
		} else {
			return;
		}
	}

	public function getParcels($data = array()) {
		$sql  = "SELECT p.parcel_id FROM `" . DB_PREFIX . "parcel` p ";
		if (isset($data['filter_parcel_status_id'])) {
			$implode = array();

			$parcel_statuses = explode(',', $data['filter_parcel_status_id']);

			foreach ($parcel_statuses as $parcel_status_id) {
				$implode[] = "p.parcel_status_id = '" . (int)$parcel_status_id . "'";
			}

			if ($implode) {
				$sql .= " WHERE (" . implode(" OR ", $implode) . ")";
			} else {

			}
		} else {
			$sql .= " WHERE p.parcel_status_id = '".$this->config->get('config_pack_status_2_id')."'";
		}

		if (!empty($data['filter_parcel_id'])) {
			$sql .= " AND p.parcel_id = '" . (int)$data['filter_parcel_id'] . "'";
		}
                
		if (!empty($data['filter_parcel_ids'])) {
			$sql .= " AND p.parcel_id IN ('" . serialize("', '", $data['filter_parcel_ids']) . "')";
		}

		if (!empty($data['filter_external_id'])) {
			$sql .= " AND p.external_id = '" . $this->db->escape($data['filter_external_id']) . "'";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(p.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if (!empty($data['filter_weight'])) {
			$sql .= " AND p.weight = '" . (float)$data['filter_weight'] . "'";
		}

		if (!empty($data['filter_total'])) {
			$sql .= " AND p.total = '" . (float)$data['filter_total'] . "'";
		}
        $sql .=" AND p.sandbox = ".$this->user->getSandbox();
        
		$sort_data = array(
			'p.parcel_id',
			'p.point',
			'status',
			'p.date_added',
			'p.total'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY p.parcel_id";
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
                
                $result = array();
                foreach($query->rows as $row) {
                        $result[] = $this->getParcel($row['parcel_id']);
                }

		return $result;
	}

	public function getParcelPacks($parcel_id) {
        //$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "parcel_pack pp WHERE pp.parcel_id = '" . (int)$parcel_id . "' GROUP BY pp.pack_id ORDER BY pp.parcel_pack_id");
		$query = $this->db->query("
        SELECT p.pack_number,pp.*, ppp.*,(pp.price*ppp.quantity) as `total` FROM `parcel_pack_product` ppp
        LEFT JOIN pack p ON (p.pack_id=ppp.pack_id)
        LEFT JOIN pack_product pp ON (pp.pack_product_id = ppp.pack_product_id)
        WHERE ppp.parcel_id = " . (int)$parcel_id . " ORDER BY p.pack_id");
		return $query->rows;
	}
    
    public function getPartnerId($parcel_id) {
        $q = $this->db->query("
        SELECT c.partner_id FROM `parcel_pack_product` ppp
        LEFT JOIN pack p ON (p.pack_id = ppp.pack_id)
        LEFT JOIN customer c ON (c.customer_id = p.customer_id)
        WHERE parcel_id = ".(int)$parcel_id." LIMIT 1");
        return $q->row['partner_id'];
    }

	public function getTotalParcels($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "parcel` p ";
		if (isset($data['filter_parcel_status_id'])) {
			$implode = array();

			$parcel_statuses = explode(',', $data['filter_parcel_status_id']);

			foreach ($parcel_statuses as $parcel_status_id) {
				$implode[] = "p.parcel_status_id = '" . (int)$parcel_status_id . "'";
			}

			if ($implode) {
				$sql .= " WHERE (" . implode(" OR ", $implode) . ")";
			} else {

			}
		} else {
			$sql .= " WHERE p.parcel_status_id = '".$this->config->get('config_pack_status_2_id')."'";
		}

		if (!empty($data['filter_parcel_id'])) {
			$sql .= " AND p.parcel_id = '" . (int)$data['filter_parcel_id'] . "'";
		}

		if (!empty($data['filter_external_id'])) {
			$sql .= " AND p.external_id = '" . $this->db->escape($data['filter_external_id']) . "'";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(p.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if (!empty($data['filter_weight'])) {
			$sql .= " AND p.weight = '" . (float)$data['filter_weight'] . "'";
		}

		if (!empty($data['filter_total'])) {
			$sql .= " AND p.total = '" . (float)$data['filter_total'] . "'";
		}
        $sql .= " AND p.sandbox = ".$this->user->getSandbox();
        
		$query = $this->db->query($sql);

		return $query->row['total'];
	}
        
        public function deleteParcel($parcel_id, $tables = array('parcel','parcel_pack_product','parcel_history')) {
                
                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "parcel_pack_product WHERE parcel_id = '" . $parcel_id . "' AND sandbox = ".$this->user->getSandbox()." GROUP BY pack_id");
                
                foreach($query->rows as $row) {
                        $this->db->query("UPDATE `" . DB_PREFIX . "pack` SET pack_status_id = ".(int)$this->config->get('config_pack_status_id')." WHERE pack_id = '" . (int)$row['pack_id'] . "' AND sandbox = ".$this->user->getSandbox());
                }
                
                foreach($tables as $table) {
                        $this->db->query("DELETE FROM `" . DB_PREFIX . $table . "` WHERE parcel_id = '" . (int)$parcel_id . "' AND sandbox = ".$this->user->getSandbox());
                }
                
                
        }


	public function getParcelHistories($parcel_id, $start = 0, $limit = 10) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}

		$query = $this->db->query("SELECT ph.*, ps.name AS parcel_status, u.username as user "
                        . " FROM " . DB_PREFIX . "parcel_history ph "
                        . " LEFT JOIN " . DB_PREFIX . "parcel_status ps ON ph.parcel_status_id = ps.parcel_status_id AND ps.language_id = '" . (int)$this->config->get('config_language_id') . "' "
                        . " LEFT JOIN user u ON u.user_id = ph.user_id "
                        . " WHERE ph.parcel_id = '" . (int)$parcel_id . "' "
                        . " ORDER BY ph.date_added ASC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	public function getTotalParcelHistories($parcel_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "parcel_history WHERE parcel_id = '" . (int)$parcel_id . "' ");
		return $query->row['total'];
	}
        
        public function _customeEditParcel($parcel_id, $data) {
                if(!empty($data['parcel_pack_product'])) {
                        foreach($data['parcel_pack_product'] as $parcel_pack_product) {
                                if (!$parcel_pack_product['quantity']) continue;
                                $sql = "INSERT INTO " . DB_PREFIX . "parcel_pack_product SET ";
                                $sql .= " parcel_id = ".$parcel_id.", ";
                                $sql .= " pack_product_id = ".$parcel_pack_product['pack_product_id'].", ";
                                $sql .= " pack_id = ".$parcel_pack_product['pack_id'].", ";
                                $sql .= " quantity = ".$parcel_pack_product['quantity'].", ";
                                $sql .= " `weight` = ".$parcel_pack_product['weight'].", ";
                                $sql .= " point =  ".$parcel_pack_product['point']." ";
                                $this->db->query($sql);
                                
                        }
                }
                $sql = "UPDATE `" . DB_PREFIX . "parcel` SET ";
                $sql .= " parcel_status_id = ".$data['parcel_status_id'].", ";
                $sql .= " comment = '".$this->db->escape($data['comment'])."'";
                $sql .= " WHERE parcel_id = '" . (int)$parcel_id . "' ";
                $this->db->query($sql);
                
//                $this->addHistory($parcel_id, $parcel_status_id, $data);
                
                $this->updateTotal($parcel_id);
        }
        
        public function customeEditParcel($parcel_id, $data) {
                $pack_deleted = false;
                $parcel_status_id = false;
                if(!empty($data['update_packs'])) {
                        $this->deleteParcel($parcel_id, array('parcel_pack'));
                        $pack_deleted = true;
                }
                
                if(!empty($data['parcel_packs'])) {
                        foreach($data['parcel_packs'] as $pack) {
                                $sql = array();
                                $query = "INSERT INTO " . DB_PREFIX . "parcel_pack SET ";
                                $where = '';
                                if(!empty($pack['parcel_pack_id']) && !$pack_deleted) {
                                        $query = "UPDATE " . DB_PREFIX . "parcel_pack SET ";
                                        $where = " WHERE parcel_pack_id = '" . (int)$pack['parcel_pack_id'] . "' ";
                                }
                                foreach($pack as $field => $value) {
                                        if(in_array($field, array('pack_id'))) {
                                                $sql[] = "`" . $field . "` = '" . (int)$value . "'";
//                                        } elseif(in_array($field, array('price', 'weight'))) {
//                                                $sql[] = "`" . $field . "` = '" . (float)$value . "'";
//                                        } else {
//                                                $sql[] = "`" . $field . "` = '" . $this->db->escape($value) . "'";
                                        }
                                }
                                if($sql) {
                                        $sql[] = "`parcel_id` = '" . (int)$parcel_id . "'";
                                        $this->db->query($query . implode(', ', $sql) . $where);
                                }
                        }
                }
                $query = "UPDATE `" . DB_PREFIX . "parcel` SET ";
                $where = " WHERE parcel_id = '" . (int)$parcel_id . "' ";
                $sql = array();
                foreach($data as $field => $value) {
                        $int = explode(',', 'parcel_status_id');
                        $float = explode(',', '');
                        $string = explode(',', 'comment,external_id');
                        if(in_array($field, $int)) {
                                $sql[] = "`" . $field . "` = '" . (int)$value . "'";
                        } elseif(in_array($field, $float)) {
                                $sql[] = "`" . $field . "` = '" . (int)$value . "'";
                        } elseif(in_array($field, $string)) {
                                $sql[] = "`" . $field . "` = '" . $this->db->escape($value) . "'";
                        }
                        if($field == 'parcel_status_id') {
                                $parcel_status_id = (int)$value;
                        }
                }
                if($sql) {
                        $this->db->query($query . implode(', ', $sql) . $where);
                }
                
                if(!$parcel_status_id) {
                        $parcel_info = $this->getParcel($parcel_id);
                        if($parcel_info) {
                                $parcel_status_id = $parcel_info['parcel_status_id'];
                        }
                }
                $this->addHistory($parcel_id, $parcel_status_id, $data);
                
                $this->updateTotal($parcel_id);
        }
        
        private function updateTotal($parcel_id) {
                $query = $this->db->query("
                SELECT 
                SUM( pp.price * ppp.quantity ) AS `total` , 
                SUM( pp.`weight` ) AS `weight` , 
                SUM( pp.`point` ) AS `point`
                FROM `" . DB_PREFIX . "pack_product` pp
                LEFT JOIN " . DB_PREFIX . "parcel_pack_product ppp ON ( ppp.pack_product_id = pp.pack_product_id)
                WHERE ppp.parcel_id =" . (int)$parcel_id);                                                                                                                                                                                                                                                      
                $this->db->query("UPDATE `" . DB_PREFIX . "parcel` SET `total` = '" . (float)$query->row['total'] . "', `weight` = '" . (float)$query->row['weight'] . "', `point` = '" . (float)$query->row['point'] . "' WHERE parcel_id = '" . (int)$parcel_id . "'");
        }
        
        public function addHistory($parcel_id, $parcel_status_id, $data) {
            
                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "parcel_history WHERE parcel_id = '" . (int)$parcel_id . "' AND parcel_status_id = '" . (int)$parcel_status_id . "' LIMIT 1");
                if($query->num_rows) {
                        $use = 0;
                } else {
                        $use = 1;
                }
                $this->load->model('localisation/pack_status');
                $pack_status_info = $this->model_localisation_pack_status->getPackStatus($parcel_status_id);

                if(!empty($pack_status_info['sms_text']) && $use) {
                        $this->load->model('sale/pack');
                        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "parcel_pack_product WHERE parcel_id = '" . $parcel_id . "' GROUP BY pack_id");
                        $tmp = $data;
                        $tmp['from_parcel'] = true;
                        
                        foreach($query->rows as $row) {
                                $this->model_sale_pack->addHistory($row['pack_id'], $parcel_status_id, $tmp);
                        }
                }
                           
                $sql  = "INSERT INTO " . DB_PREFIX . "parcel_history SET "
                        . "date_added = NOW(), "
                        . "`use` = '" . (int)$use . "', "
                        . "parcel_id = '" . (int)$parcel_id . "', "
                        . "parcel_status_id = '" . (int)$parcel_status_id . "', "
                        . "user_id = '" . (int)$this->user->getId() . "', "
                        . "`data` = '" . $this->db->escape(serialize($data)) . "'";
                $this->db->query($sql);
                
                $sql  = "UPDATE " . DB_PREFIX . "parcel SET "
                        . "parcel_status_id = '" . (int)$parcel_status_id . "' "
                        . "WHERE parcel_id = '" . (int)$parcel_id . "'";
                $this->db->query($sql);
        }

}