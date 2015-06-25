<?php
class ModelSaleCategoryGroup extends Model {
	public function addCategoryGroup($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "category_group SET name = '" . $this->db->escape($data['name']) . "'");
		return $this->db->getLastId();
	}

	public function editCategoryGroup($category_group_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "category_group SET name = '" . $this->db->escape($data['name']) . "' WHERE category_group_id = '" . (int)$category_group_id . "'");
	}

	public function deleteCategoryGroup($category_group_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "category_group WHERE category_group_id = '" . (int)$category_group_id . "'");
	}

	public function getCategoryGroup($category_group_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_group WHERE category_group_id = '" . (int)$category_group_id . "'");
		return $query->row;
	}

	public function getCategoryGroups($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "category_group WHERE 1";

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
			$query = $this->db->query("SELECT category_group_id, name FROM " . DB_PREFIX . "category_group WHERE 1 ORDER BY name");
			return $query->rows;
		}
	}

	public function getTotalCategoryGroups() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "category_group WHERE 1");
		return $query->row['total'];
	}
}