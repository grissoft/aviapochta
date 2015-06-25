<?php
class ModelAccountPack extends Model {
	public function getPack($pack_id, $by_external = false) {
                /*if($by_external) {
                        $pack_query = $this->db->query("SELECT *, (SELECT cg.name FROM " . DB_PREFIX . "category_group cg WHERE cg.category_group_id = p.category_group_id) as category_group, (SELECT CONCAT(c.firstname, ' ', c.lastname) FROM " . DB_PREFIX . "customer c WHERE c.customer_id = p.customer_id) AS customer, (SELECT ps.name FROM " . DB_PREFIX . "pack_status ps WHERE ps.pack_status_id = p.pack_status_id AND ps.language_id = '" . (int)$this->config->get('config_language_id') . "') AS pack_status FROM `" . DB_PREFIX . "pack` p WHERE p.external_id = '" . $this->db->escape($pack_id) . "'");
                } else {
                        $pack_query = $this->db->query("SELECT *, (SELECT cg.name FROM " . DB_PREFIX . "category_group cg WHERE cg.category_group_id = p.category_group_id) as category_group, (SELECT CONCAT(c.firstname, ' ', c.lastname) FROM " . DB_PREFIX . "customer c WHERE c.customer_id = p.customer_id) AS customer, (SELECT ps.name FROM " . DB_PREFIX . "pack_status ps WHERE ps.pack_status_id = p.pack_status_id AND ps.language_id = '" . (int)$this->config->get('config_language_id') . "') AS pack_status FROM `" . DB_PREFIX . "pack` p WHERE p.pack_id = '" . (int)$pack_id . "'");
                }*/
                $pack_query = $this->db->query("SELECT *, (SELECT cg.name FROM " . DB_PREFIX . "category_group cg WHERE cg.category_group_id = p.category_group_id) as category_group, (SELECT CONCAT(c.firstname, ' ', c.lastname) FROM " . DB_PREFIX . "customer c WHERE c.customer_id = p.customer_id) AS customer, (SELECT ps.name FROM " . DB_PREFIX . "pack_status ps WHERE ps.pack_status_id = p.pack_status_id AND ps.language_id = '" . (int)$this->config->get('config_language_id') . "') AS pack_status FROM `" . DB_PREFIX . "pack` p WHERE p.pack_number = '" . (int)$pack_id . "'");
		if ($pack_query->num_rows) {

			$pack_products = $this->getPackProducts($pack_query->row['pack_id']);
                        $pack_query->row['product_count'] = count($pack_products);
                        $pack_query->row['products'] = $pack_products;
                        $pack_query->row['last_comment'] = '';
                        $last_history = $this->getPackHistories($pack_query->row['pack_id'], 0, 1);
                        if($last_history) {
                                $pack_query->row['pack_status_is'] = $last_history[0]['pack_status_id'];
                                $pack_query->row['pack_status'] = $last_history[0]['pack_status'];
                                $pack_query->row['last_comment'] = $last_history[0]['comment'];
                        }
                        
			return $pack_query->row;
		} else {
			return;
		}
        }
        
	public function getPackProducts($pack_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "pack_product pp WHERE pp.pack_id = '" . (int)$pack_id . "' ORDER BY pack_product_id");
		return $query->rows;
	}

	public function getPackHistories($pack_id, $start = 0, $limit = 0) {
                $limit = '';
		if ($start >= 0 && $limit > 0) {
                        $limit = "LIMIT " . (int)$start . "," . (int)$limit;
		}
      //  echo "SELECT ph.*, ps.name AS pack_status, u.username as user "
//                        . "FROM " . DB_PREFIX . "pack_history ph "
//                        . "LEFT JOIN " . DB_PREFIX . "pack_status ps ON ph.pack_status_id = ps.pack_status_id AND ps.language_id = '" . (int)$this->config->get('config_language_id') . "' "
//                        . "LEFT JOIN user u ON u.user_id = ph.user_id "
//                        . "WHERE ph.pack_id = '" . (int)$pack_id . "' "
//                        
//                        . "AND ps.customer_notify = '1'"
//                        . "ORDER BY ph.date_added DESC " . $limit; exit;
		$query = $this->db->query("SELECT ph.*, ps.name AS pack_status, u.username as user "
                        . "FROM " . DB_PREFIX . "pack_history ph "
                        . "LEFT JOIN " . DB_PREFIX . "pack_status ps ON ph.pack_status_id = ps.pack_status_id AND ps.language_id = '" . (int)$this->config->get('config_language_id') . "' "
                        . "LEFT JOIN user u ON u.user_id = ph.user_id "
                        . "WHERE ph.pack_id = '" . (int)$pack_id . "' "
                        
                        . "AND ps.customer_notify = '1'"
                        . "ORDER BY ph.date_added DESC " . $limit);

		return $query->rows;
	}

	public function getTotalPackHistories($pack_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "pack_history WHERE pack_id = '" . (int)$pack_id . "'");

		return $query->row['total'];
	}
        

}
?>