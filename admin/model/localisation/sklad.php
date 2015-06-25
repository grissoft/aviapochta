<?php
class ModelLocalisationSklad extends Model {
	public function addSklad($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "sklad SET date_added = NOW(), name = '" . $this->db->escape($data['name']) . "', address = '" . $this->db->escape($data['address']) . "'");
		return $this->db->getLastId();
	}

	public function editSklad($sklad_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "sklad SET name = '" . $this->db->escape($data['name']) . "', address = '" . $this->db->escape($data['address']) . "' WHERE sklad_id = '" . (int)$sklad_id . "'");
	}

	public function deleteSklad($sklad_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "sklad WHERE sklad_id = '" . (int)$sklad_id . "'");
	}

	public function getSklad($sklad_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "sklad WHERE sklad_id = '" . (int)$sklad_id . "'");
		return $query->row;
	}

	public function getSklades($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "sklad WHERE 1";

			$sql .= " ORDER BY name";

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
		} else {
			$query = $this->db->query("SELECT sklad_id, name, address FROM " . DB_PREFIX . "sklad WHERE 1 ORDER BY name");
			return $query->rows;
		}
	}

	public function getTotalSklades() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "sklad WHERE 1");
		return $query->row['total'];
	}
}