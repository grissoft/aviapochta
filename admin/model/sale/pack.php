<?php
class ModelSalePack extends Model {
    
        public function addPack($data) {
                $pack_number = $this->getAutoincPackNubmer((int)$data['customer_id']);
                $this->db->query("INSERT INTO `" . DB_PREFIX . "pack` SET 
                `sandbox` = ".(int)$this->user->getSandbox().", date_added = NOW(), pack_number = '".$pack_number."'");
                $pack_id = $this->db->getLastId();
                
                $this->editPack($pack_id, $data);
                $this->addHistory($pack_id, $this->config->get('config_pack_status_id'), $data);
                return $pack_id;
        }
        
        public function editPack($pack_id, $data) {
                $data['update_products'] = 1;
                $this->customeEditPack($pack_id, $data);
        }
    public function getAutoincPackNubmer($customer_id = 0) {
        $q = $this->db->query("SELECT * FROM `".DB_PREFIX."customer` WHERE customer_id = '".(int)$customer_id."'");
        if ($q->num_rows) {
            $partner_id = $q->row['partner_id'];
        } else {
            $partner_id = 0;
        }
        if ($this->user->getSandbox()) {
            $table_name = "partner_inc_".$partner_id;
        } else {
            $table_name = "partner_inc_".$partner_id."_sandbox";
        }            
        
        $q = $this->db->query("SHOW tables like '".$table_name."'");
        if (!$q->num_rows) {
           $this->db->query(
               "CREATE TABLE IF NOT EXISTS `".$table_name."` (
               `autoinc_id` int(11) NOT NULL AUTO_INCREMENT,
               `number` varchar(100) CHARACTER SET utf8 NOT NULL,
                PRIMARY KEY (`autoinc_id`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8"
           ); 
        }
        
        if ($partner_id) {
            $q = $this->db->query("SELECT * FROM `partner` WHERE partner_id = '".(int)$partner_id."'");
            if ($q->num_rows) {
                $prefix = $q->row['prefix'];
            } else {
                $prefix = "";
            }
        } else {
            $prefix = $this->config->get('config_prefix');
        }
        $this->db->query("INSERT INTO `" . $table_name . "` SET number = ''");
        $inc = $this->db->getLastId();
        $number =  $prefix . str_pad($inc, 9-strlen($prefix), "0", STR_PAD_LEFT); 
        $this->db->query("UPDATE  `" . $table_name . "` SET number = '".$number."' WHERE autoinc_id = ".$inc);
        return $number;
        
        
        
    }
	public function getPack($pack_id,$products = true) {
        $sql = "SELECT 
        IF(c.firstname is NULL,'Неизвестно',CONCAT(c.firstname, ' ', c.lastname)) as customer,c.partner_id, p.*,
        p.total-SUM(pp.price*ppp.quantity) as total_ost,
        p.weight-SUM(pp.weight*ppp.quantity) as weight_ost,
        p.point-SUM(pp.point*ppp.quantity) as point_ost,
        (SELECT ps.name FROM " . DB_PREFIX . "pack_status ps WHERE  ps.pack_status_id = p.pack_status_id AND ps.language_id = '" . (int)$this->config->get('config_language_id') . "') AS pack_status 
        FROM " . DB_PREFIX . "pack p
        LEFT JOIN " . DB_PREFIX . "customer c ON c.customer_id = p.customer_id
        LEFT JOIN " . DB_PREFIX . "pack_product pp ON p.pack_id = pp.pack_id
        LEFT JOIN " . DB_PREFIX . "parcel_pack_product ppp ON ppp.pack_product_id = pp.pack_product_id
        WHERE p.pack_id = '" . (int)$pack_id . "' AND p.`sandbox` = '".$this->user->getSandbox()."'
        GROUP BY p.pack_id";
		$pack_query = $this->db->query($sql);

		if ($pack_query->num_rows) {
            if ($products) {
			    $pack_products = $this->getPackProducts($pack_id);
                            $pack_query->row['product_count'] = count($pack_products);
                            $pack_query->row['products'] = $pack_products;
                            if(!(int)$pack_query->row['customer_id']) {
                                    $pack_query->row['customer'] = 'Неизвестный';
                            }
                                    
			    $this->load->model('localisation/language');

			    $language_info = $this->model_localisation_language->getLanguage($pack_query->row['language_id']);

			    if ($language_info) {
				    $language_code = $language_info['code'];
				    $language_directory = $language_info['directory'];
			    } else {
				    $language_code = '';
				    $language_directory = '';
			    }

                            $pack_query->row['language_code']       = $language_code;
			    $pack_query->row['language_directory']  = $language_directory;
            }
			return $pack_query->row;
		} else {
			return;
		}
	}

	public function getPacks($data = array()) {
		$sql  = "SELECT p.pack_id, CONCAT(c.firstname, ' ', c.lastname) AS customer,c.partner_id FROM `" . DB_PREFIX . "pack` p ";
        $sql .= "LEFT JOIN " . DB_PREFIX . "customer c ON c.customer_id = p.customer_id ";
		
        if (isset($data['filter_pack_status_id'])) {
			$implode = array();

			$pack_statuses = explode(',', $data['filter_pack_status_id']);

			foreach ($pack_statuses as $pack_status_id) {
				$implode[] = "p.pack_status_id = '" . (int)$pack_status_id . "'";
			}

			if ($implode) {
				$sql .= " WHERE (" . implode(" OR ", $implode) . ")";
			} else {

			}
		} else {
			$sql .= " WHERE p.pack_status_id = ".(int)$this->config->get('config_pack_status_id');
		}

		if (!empty($data['filter_free'])) {
			$sql .= " AND p.pack_id NOT IN (SELECT p_p.pack_id FROM " . DB_PREFIX . "parcel_pack p_p LEFT JOIN " . DB_PREFIX . "parcel pl ON pl.parcel_id = p_p.parcel_id WHERE pl.parcel_status_id > 0)";
		}
                
		if (!empty($data['filter_pack_id'])) {
			$sql .= " AND p.pack_id = '" . (int)$data['filter_pack_id'] . "'";
		}
                
		if (!empty($data['filter_name'])) {
			$sql .= " AND (";
			$sql .= " (IF(p.customer_id = 0, 'Неизвестно', CONCAT(c.firstname, ' ', c.lastname)) LIKE '" . $this->db->escape($data['filter_name']) . "%')";
			$sql .= " OR (p.pack_id = '" . (int)$data['filter_name'] . "')";
//			$sql .= " OR (p.date_added = '" . $this->db->escape($data['filter_name']) . "')";
			$sql .= " )";
		}
                
		if (!empty($data['filter_pack_ids'])) {
			$sql .= " AND p.pack_id IN ('" . implode("', '", $data['filter_pack_ids']) . "')";
		}

		if (!empty($data['filter_external_id'])) {
			$sql .= " AND p.external_id = '" . $this->db->escape($data['filter_external_id']) . "'";
		}

		if (!empty($data['filter_customer'])) {
                        if($data['filter_customer'] == 'Неизвестный') {
                                $sql .= " AND p.customer_id = '0'";
                        } else {
                                $sql .= " AND CONCAT(c.firstname, ' ', c.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
                        }
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
        
        $sql .= " AND p.`sandbox` = '".$this->user->getSandbox()."' ";
        
		$sort_data = array(
			'p.pack_id',
			'p.point',
			'customer',
			'status',
			'p.date_added',
			'p.total'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY p.pack_id";
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
                        $result[] = $this->getPack($row['pack_id'],false);
                }

		return $result;
	}

	public function getPackProducts($pack_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "pack_product pp 
        WHERE pp.pack_id = '" . (int)$pack_id . "' ORDER BY pack_product_id");
		return $query->rows;
	}

	public function getTotalPacks($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "pack` p ";
        $sql .= "LEFT JOIN " . DB_PREFIX . "customer c ON c.customer_id = p.customer_id ";
		
        if (isset($data['filter_pack_status_id'])) {
			$implode = array();

			$pack_statuses = explode(',', $data['filter_pack_status_id']);

			foreach ($pack_statuses as $pack_status_id) {
				$implode[] = "p.pack_status_id = '" . (int)$pack_status_id . "'";
			}

			if ($implode) {
				$sql .= " WHERE (" . implode(" OR ", $implode) . ")";
			} else {

			}
		} else {
			$sql .= " WHERE p.pack_status_id = ".(int)$this->config->get('config_pack_status_id');
		}

		if (!empty($data['filter_pack_id'])) {
			$sql .= " AND p.pack_id = '" . (int)$data['filter_pack_id'] . "'";
		}

		if (!empty($data['filter_pack_ids'])) {
			$sql .= " AND p.pack_id IN ('" . implode("', '", $data['filter_pack_ids']) . "')";
		}

		if (!empty($data['filter_external_id'])) {
			$sql .= " AND p.external_id = '" . $this->db->escape($data['filter_external_id']) . "'";
		}

		if (!empty($data['filter_customer'])) {
                        if($data['filter_customer'] == 'Неизвестный') {
                                $sql .= " AND p.customer_id = '0'";
                        } else {
                                $sql .= " AND CONCAT(c.firstname, ' ', c.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
                        }
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
        $sql .= " AND p.`sandbox` = '".$this->user->getSandbox()."' ";
        
		$query = $this->db->query($sql);

		return $query->row['total'];
	}
        
        public function deletePack($pack_id, $tables = array('pack','pack_product','pack_history')) {
                $q = $this->db->query("SELECT * FROM `" . DB_PREFIX . "parcel_pack_product` WHERE pack_id = '" . (int)$pack_id . "' AND `sandbox` = ".(int)$this->user->getSandbox()." LIMIT 1");
                if ($q->num_rows) return false;
                foreach($tables as $table) {
                        $this->db->query("DELETE FROM `" . DB_PREFIX . $table . "` WHERE pack_id = '" . (int)$pack_id . "' AND `sandbox` = ".(int)$this->user->getSandbox());
                }
                return true;
        }
    /*
	public function getTotalPacksByPackStatusId($pack_status_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "pack_history` WHERE pack_status_id = '" . (int)$pack_status_id . "' AND pack_status_id > '0'");

		return $query->row['total'];
	}

	public function getTotalPacksBySkladId($sklad_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "pack` WHERE sklad_id = '" . (int)$sklad_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByCategoryGroupId($category_group_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "pack` WHERE category_group_id = '" . (int)$category_group_id . "'");

		return $query->row['total'];
	}*/

	public function getPackHistories($pack_id, $start = 0, $limit = 10) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}

		$query = $this->db->query("SELECT ph.*, ps.name AS pack_status, u.username as user "
                        . " FROM " . DB_PREFIX . "pack_history ph "
                        . " LEFT JOIN " . DB_PREFIX . "pack_status ps ON ph.pack_status_id = ps.pack_status_id AND ps.language_id = '" . (int)$this->config->get('config_language_id') . "' "
                        . " LEFT JOIN user u ON u.user_id = ph.user_id "
                        . " WHERE ph.pack_id = '" . (int)$pack_id . "' "
                        . ""
                        . " ORDER BY ph.date_added ASC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	public function getTotalPackHistories($pack_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "pack_history WHERE pack_id = '" . (int)$pack_id . "' ");

		return $query->row['total'];
	}
        
        public function customeEditPack($pack_id, $data) {
                $product_deleted = false;
                $pack_status_id = false;
                if(!empty($data['update_products'])) {
                        $this->deletePack($pack_id, array('pack_product'));
                        $product_deleted = true;
                }
                if(!empty($data['products'])) {
                        foreach($data['products'] as $product) {
                                $sql = array();
                                $query = "INSERT INTO " . DB_PREFIX . "pack_product SET ";
                                $where = '';
                                if(!empty($product['pack_product_id']) && !$product_deleted) {
                                        $query = "UPDATE " . DB_PREFIX . "pack_product SET ";
                                        $where = " WHERE pack_product_id = '" . (int)$product['pack_product_id'] . "' AND sandbox = ".$this->user->getSandbox();
                                }
                                foreach($product as $field => $value) {
                                        if(in_array($field, array('quantity', 'category_group_id'))) {
                                                $sql[] = "`" . $field . "` = '" . (int)$value . "'";
                                        } elseif(in_array($field, array('price', 'weight'))) {
                                                $sql[] = "`" . $field . "` = '" . (float)$value . "'";
                                        } else {
                                                $sql[] = "`" . $field . "` = '" . $this->db->escape($value) . "'";
                                        }
                                }
                                if($sql) {
                                        $sql[] = "`pack_id` = '" . (int)$pack_id . "'";
                                        $this->db->query($query . implode(', ', $sql) . $where);
                                }
                        }
                }
                $query = "UPDATE `" . DB_PREFIX . "pack` SET ";
                $where = " WHERE pack_id = '" . (int)$pack_id . "'";
                $sql = array();
                foreach($data as $field => $value) {
                        $int = explode(',', 'customer_id,sklad_id,pack_status_id,language_id,currency_id,category_group_id');
                        $float = explode(',', 'currency_value');
                        $string = explode(',', 'currency_code,comment,external_id');
                        if(in_array($field, $int)) {
                                $sql[] = "`" . $field . "` = '" . (int)$value . "'";
                        } elseif(in_array($field, $float)) {
                                $sql[] = "`" . $field . "` = '" . (int)$value . "'";
                        } elseif(in_array($field, $string)) {
                                $sql[] = "`" . $field . "` = '" . $this->db->escape($value) . "'";
                        }
                        if($field == 'pack_status_id') {
                                $pack_status_id = (int)$value;
                        }
                }
                if($sql) {
                        $this->db->query($query . implode(', ', $sql) . $where);
                }
                
                //if(!$pack_status_id) {
//                        $pack_info = $this->getPack($pack_id);
//                        if($pack_info) {
//                                $pack_status_id = $pack_info['pack_status_id'];
//                        }
//                }
//                $this->addHistory($pack_id, $pack_status_id, $data);
                
                $this->updateTotal($pack_id);
        }
        
        private function updateTotal($pack_id) {
                $query = $this->db->query("SELECT SUM(pp.price * pp.quantity) as `total`, SUM(pp.`weight`) as `weight`, SUM(pp.`point`) as `point` FROM `" . DB_PREFIX . "pack_product` pp 
                WHERE pp.pack_id = '" . (int)$pack_id . "'");
                $this->db->query("UPDATE `" . DB_PREFIX . "pack` SET `total` = '" . (float)$query->row['total'] . "', `weight` = '" . (float)$query->row['weight'] . "', `point` = '" . (float)$query->row['point'] . "' WHERE pack_id = '" . (int)$pack_id . "'");
        }
        
        public function addHistory($pack_id, $pack_status_id, $data = array()) {
                if (!$this->packFullDeriban($pack_id) && $pack_status_id==$this->config->get('config_pack_status_2_id')) return;
                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "pack_history WHERE pack_id = '" . (int)$pack_id . "' AND pack_status_id = '" . (int)$pack_status_id . "' AND `use` = 1 ");
                if($query->num_rows) {
                        $use = 0;
                } else {
                        $use = 1;
                }
             //   $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "pack_history WHERE pack_id = '" . (int)$pack_id . "' AND pack_status_id = '" . (int)$pack_status_id . "' AND `notify` = 1");
//                $notify = 0;
//                if(!$query->num_rows) {
//                        $notify = 1;
//                }
                $this->load->model('localisation/pack_status');
                $pack_status_info = $this->model_localisation_pack_status->getPackStatus($pack_status_id);
                if(!empty($pack_status_info['sms_text']) && $use) {
                        if($this->config->get('config_sms_pack')) {
                                $pack_info = $this->getPack($pack_id);
                                $this->load->model('sale/customer');
                                $customer_info = $this->model_sale_customer->getCustomer($pack_info['customer_id']);
                                if($customer_info && $pack_info) {
                                        $text = $this->format->formatPack($pack_status_info['sms_text'], $pack_info);
                                        $text = $this->format->formatCustomer($text, $customer_info);
                                        $this->sms->add(array('telephone' => $customer_info['telephone'], 'text' => $text));
                                }
                        }
                } 
                $comment = '';
                if(!empty($pack_status_info['next_status_text'])) {
                        $comment = $this->format->formatDate($pack_status_info['next_status_text']);
                }
                $sql  = "INSERT INTO " . DB_PREFIX . "pack_history SET "
                        . "date_added = NOW(), "
                        . "`use` = '" . (int)$use . "', "
                        . "pack_id = '" . (int)$pack_id . "', "
                        . "pack_status_id = '" . (int)$pack_status_id . "', "
                        . "user_id = '" . (int)$this->user->getId() . "', "
                        . "comment = '" . $this->db->escape($comment) . "', "
                        . "`data` = '" . $this->db->escape(serialize($data)) . "'";
                $this->db->query($sql);
                
                $sql  = "UPDATE " . DB_PREFIX . "pack SET "
                        . "pack_status_id = '" . (int)$pack_status_id . "' "
                        . "WHERE pack_id = '" . (int)$pack_id . "'";
                $this->db->query($sql);
                
        }
        
     public function packFullDeriban($pack_id) {
        $q = $this->db->query("SELECT sum( pp.quantity - (
        SELECT IF( sum( quantity ) IS NULL , 0, sum( quantity ) )
        FROM parcel_pack_product
        WHERE pack_product_id = pp.pack_product_id ) ) AS quantity_ost
        FROM pack_product pp
        WHERE pp.pack_id = ".$pack_id);
        return $q->row['quantity_ost']==0;
    }

}