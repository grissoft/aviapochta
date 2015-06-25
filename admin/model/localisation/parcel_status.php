<?php
class ModelLocalisationParcelStatus extends Model {
	public function addParcelStatus($data) {
		foreach ($data['parcel_status'] as $language_id => $value) {
			if (isset($parcel_status_id)) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "parcel_status SET parcel_status_id = '" . (int)$pack_status_id . "', language_id = '" . (int)$language_id . "', pack_status_id = '" . (int)$data['pack_status_id'] . "', name = '" . $this->db->escape($value['name']) . "'");
			} else {
				$this->db->query("INSERT INTO " . DB_PREFIX . "parcel_status SET language_id = '" . (int)$language_id . "', pack_status_id = '" . (int)$data['pack_status_id'] . "', name = '" . $this->db->escape($value['name']) . "'");

				$pack_status_id = $this->db->getLastId();
			}
		}

		$this->cache->delete('parcel_status');
	}

	public function editParcelStatus($parcel_status_id, $data) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "parcel_status WHERE parcel_status_id = '" . (int)$parcel_status_id . "'");

		foreach ($data['parcel_status'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "parcel_status SET parcel_status_id = '" . (int)$parcel_status_id . "', language_id = '" . (int)$language_id . "', pack_status_id = '" . (int)$data['pack_status_id'] . "', name = '" . $this->db->escape($value['name']) . "'");
		}

		$this->cache->delete('parcel_status');
	}

	public function deleteParcelStatus($parcel_status_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "parcel_status WHERE parcel_status_id = '" . (int)$parcel_status_id . "'");

		$this->cache->delete('parcel_status');
	}

	public function getParcelStatus($parcel_status_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "parcel_status WHERE parcel_status_id = '" . (int)$parcel_status_id . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getParcelStatuses($data = array()) {
		if ($data) {
			$sql = "SELECT ps.*, ps2.name as pack_status FROM " . DB_PREFIX . "parcel_status ps "
                                . " LEFT JOIN " . DB_PREFIX . "pack_status ps2 ON ps2.pack_status_id = ps.pack_status_id"
                                . " WHERE ps.language_id = '" . (int)$this->config->get('config_language_id') . "'";

			$sql .= " ORDER BY ps.name";

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
			$parcel_status_data = $this->cache->get('parcel_status.' . (int)$this->config->get('config_language_id'));

			if (!$parcel_status_data) {
				$query = $this->db->query("SELECT parcel_status_id, name FROM " . DB_PREFIX . "parcel_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY name");

				$parcel_status_data = $query->rows;

				$this->cache->set('parcel_status.' . (int)$this->config->get('config_language_id'), $parcel_status_data);
			}

			return $parcel_status_data;
		}
	}

	public function getParcelStatusDescriptions($parcel_status_id) {
		$parcel_status_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "parcel_status WHERE parcel_status_id = '" . (int)$parcel_status_id . "'");

		foreach ($query->rows as $result) {
			$parcel_status_data[$result['language_id']] = array('name' => $result['name'], 'pack_status_id' => $result['pack_status_id']);
		}

		return $parcel_status_data;
	}

	public function getTotalParcelStatuses() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "parcel_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row['total'];
	}
}