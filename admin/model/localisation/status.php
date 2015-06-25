<?php
class ModelLocalisationPackStatus extends Model {
	public function addPackStatus($data) {
		foreach ($data['pack_status'] as $language_id => $value) {
			if (isset($pack_status_id)) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "pack_status SET pack_status_id = '" . (int)$pack_status_id . "', language_id = '" . (int)$language_id . "', sms_text = '" . $this->db->escape($data['sms_text']) . "', name = '" . $this->db->escape($value['name']) . "', customer_notify = '" . (int)$data['customer_notify'] . "', next_status_text = '" . $this->db->escape($data['next_status_text']) . "'");
			} else {
				$this->db->query("INSERT INTO " . DB_PREFIX . "pack_status SET language_id = '" . (int)$language_id . "', sms_text = '" . $this->db->escape($data['sms_text']) . "', name = '" . $this->db->escape($value['name']) . "', customer_notify = '" . (int)$data['customer_notify'] . "', next_status_text = '" . $this->db->escape($data['next_status_text']) . "'");

				$pack_status_id = $this->db->getLastId();
			}
		}

		$this->cache->delete('pack_status');
	}

	public function editPackStatus($pack_status_id, $data) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "pack_status WHERE pack_status_id = '" . (int)$pack_status_id . "'");

		foreach ($data['pack_status'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "pack_status SET pack_status_id = '" . (int)$pack_status_id . "', language_id = '" . (int)$language_id . "', sms_text = '" . $this->db->escape($data['sms_text']) . "', name = '" . $this->db->escape($value['name']) . "', customer_notify = '" . (int)$data['customer_notify'] . "', next_status_text = '" . $this->db->escape($data['next_status_text']) . "'");
		}

		$this->cache->delete('pack_status');
	}

	public function deletePackStatus($pack_status_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "pack_status WHERE pack_status_id = '" . (int)$pack_status_id . "'");

		$this->cache->delete('pack_status');
	}

	public function getPackStatus($pack_status_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "pack_status WHERE pack_status_id = '" . (int)$pack_status_id . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getPackStatuses($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "pack_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'";

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
			$pack_status_data = $this->cache->get('pack_status.' . (int)$this->config->get('config_language_id'));

			if (!$pack_status_data) {
				$query = $this->db->query("SELECT pack_status_id, name FROM " . DB_PREFIX . "pack_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY name");

				$pack_status_data = $query->rows;

				$this->cache->set('pack_status.' . (int)$this->config->get('config_language_id'), $pack_status_data);
			}

			return $pack_status_data;
		}
	}

	public function getPackStatusDescriptions($pack_status_id) {
		$pack_status_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "pack_status WHERE pack_status_id = '" . (int)$pack_status_id . "'");

		foreach ($query->rows as $result) {
			$pack_status_data[$result['language_id']] = array('name' => $result['name'], 'sms_text' => $result['sms_text'], 'next_status_text' => $result['next_status_text'], 'customer_notify' => $result['customer_notify']);
		}

		return $pack_status_data;
	}

	public function getTotalPackStatuses() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "pack_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row['total'];
	}
}