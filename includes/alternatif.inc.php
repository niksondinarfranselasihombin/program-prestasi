<?php
class Alternatif {
	private $conn;
	private $table_name = "data_alternatif";

	public $id;
	public $nik;
	public $nama;
	public $tempat_lahir;
	public $tanggal_lahir;
	public $kelamin;
	public $alamat;
	
	public function __construct($db) {
		$this->conn = $db;
	}

	function insert() {
		$query = "INSERT INTO {$this->table_name} VALUES(?, ?, ?, NULL)";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->id);
		$stmt->bindParam(2, $this->nik);
		$stmt->bindParam(3, $this->nama);

		if ($stmt->execute()) {
			return true;
		} else {
			return false;
		}
	}

	function readAll() {
		$query = "SELECT * FROM {$this->table_name} ORDER BY id_alternatif ASC";
		$stmt = $this->conn->prepare( $query );
		$stmt->execute();

		return $stmt;
	}

	function readByFilter() {
		$query = "SELECT * FROM {$this->table_name} a JOIN nilai_awal b ON a.id_alternatif=b.id_alternatif WHERE b.keterangan='B'";
		$stmt = $this->conn->prepare( $query );
		$stmt->execute();

		return $stmt;
	}

	function countByFilter() {
		$query = "SELECT * FROM {$this->table_name} a JOIN nilai_awal b ON a.id_alternatif=b.id_alternatif WHERE b.keterangan='B' ";
		$stmt = $this->conn->prepare( $query );
		$stmt->execute();

		return $stmt->rowCount();
	}

	function readAllWithNilai() {
		$query = "SELECT *, b.nilai, b.keterangan
				FROM {$this->table_name} a
					JOIN nilai_awal b ON a.id_alternatif=b.id_alternatif
				WHERE a.id_alternatif IN (SELECT id_alternatif FROM nilai_awal)
				ORDER BY a.id_alternatif";
		$stmt = $this->conn->prepare($query);
		$stmt->execute();

		return $stmt;
	}

	function readByRank() {
		$query = "SELECT *
				FROM {$this->table_name} a
					JOIN nilai_awal b ON a.id_alternatif=b.id_alternatif
				WHERE b.keterangan='B'
					AND b.periode=?
				ORDER BY hasil_akhir DESC
				LIMIT 0,5";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->periode);
		$stmt->execute();

		return $stmt;
	}

	function countAll(){
		$query = "SELECT * FROM {$this->table_name} ORDER BY id_alternatif ASC";
		$stmt = $this->conn->prepare( $query );
		$stmt->execute();

		return $stmt->rowCount();
	}

	function readOne(){
		$query = "SELECT * FROM {$this->table_name} WHERE id_alternatif=? LIMIT 0,1";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->id);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		$this->id = $row["id_alternatif"];
		$this->nik = $row["nik"];
		$this->nama = $row["nama"];
	//	$this->tempat_lahir = $row["tempat_lahir"];
	//	$this->tanggal_lahir = $row["tanggal_lahir"];
	//	$this->kelamin = $row["kelamin"];
	//	$this->alamat = $row["alamat"];
	//	$this->jabatan = $row["jabatan"];
		
		// $this->skor_alternatif = $row['skor_alternatif'];
	}

	function readOneByNik(){
		$query = "SELECT * FROM {$this->table_name} WHERE nik=? LIMIT 0,1";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->nik);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		$this->id = $row["id_alternatif"];
		$this->nik = $row["nik"];
		$this->nama = $row["nama"];
	//	$this->tempat_lahir = $row["tempat_lahir"];
	//	$this->tanggal_lahir = $row["tanggal_lahir"];
	//	$this->kelamin = $row["kelamin"];
	//	$this->alamat = $row["alamat"];
//		$this->jabatan = $row["jabatan"];
		// $this->skor_alternatif = $row['skor_alternatif'];
	}

	function readSatu($a) {
		$query = "SELECT * FROM {$this->table_name} WHERE id_alternatif='$a' LIMIT 0,1";
		$stmt = $this->conn->prepare( $query );
		$stmt->execute();

		return $stmt;
	}

	function getNewID() {
		$query = "SELECT MAX(id_alternatif) AS code FROM {$this->table_name}";
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if ($row) {
			return $this->genCode($row["code"], 'A', 3);
		} else {
			return $this->genCode($nomor_terakhir, 'A', 3);
		}
	}

	function genCode($latest, $key, $chars = 0) {
    $new = intval(substr($latest, strlen($key))) + 1;
    $numb = str_pad($new, $chars, "0", STR_PAD_LEFT);
    return $key . $numb;
	}

	function genNextCode($start, $key, $chars = 0) {
    $new = str_pad($start, $chars, "0", STR_PAD_LEFT);
    return $key . $new;
	}

	function update() {
		$query = "UPDATE {$this->table_name}
				SET
					nik = :nik,
					nama = :nama,
				WHERE
					id_alternatif = :id";
		$stmt = $this->conn->prepare($query);

		$stmt->bindParam(':nik', $this->nik);
		$stmt->bindParam(':nama', $this->nama);
	//	$stmt->bindParam(':tempat_lahir', $this->tempat_lahir);
	//	$stmt->bindParam(':tanggal_lahir', $this->tanggal_lahir);
	//	$stmt->bindParam(':kelamin', $this->kelamin);
	//	$stmt->bindParam(':alamat', $this->alamat);
		$stmt->bindParam(':id', $this->id);

		if ($stmt->execute()) {
			return true;
		} else {
			return false;
		}
	}

	function delete() {
		$query = "DELETE FROM {$this->table_name} WHERE id_alternatif = ?";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->id);
		if ($result = $stmt->execute()) {
			return true;
		} else {
			return false;
		}
	}

	function hapusell($ax) {
		$query = "DELETE FROM {$this->table_name} WHERE id_alternatif in $ax";
		$stmt = $this->conn->prepare($query);
		if ($result = $stmt->execute()) {
			return true;
		} else {
			return false;
		}
	}
}
