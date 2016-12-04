<?php
session_start();
require_once 'database.php';

class auth
{
    private $db;

    /**
     * auth constructor.
     * @param $db
     */
    public function __construct()
    {
        $conn = new database();
        $this->db = $conn->connectDB();
    }

    public function login($username , $pass)
    {

        if(empty($_POST['username']) || empty($_POST['pass']))
        {
            $error = 'Username atau Password Anda Kosong';
            $_SESSION['error'] = $error;
            header('location: login.php');
        } else {
            try {
                $query_dosen = "select * from sisidang.dosen d where d.username=:username and d.password=:password limit 1 ";
                $hasil_dosen = $this->getDb()->prepare($query_dosen);
                $hasil_dosen->execute(array(':username' => $username, ':password' => $pass));
                $hasil_dosen_row = $hasil_dosen->fetch(PDO::FETCH_ASSOC);
                $query_mahasiswa = "select * from sisidang.mahasiswa d where d.username=:username and d.password=:password limit 1 ";
                $hasil_mahasiswa = $this->getDb()->prepare($query_mahasiswa);
                $hasil_mahasiswa->execute(array(':username' => $username, ':password' => $pass));
                $hasil_mahasiswa_row = $hasil_mahasiswa->fetch(PDO::FETCH_ASSOC);

                if ($username === 'admin' && $pass === 'admin') {
                    $array = array('admin');
                    $_SESSION['role'] = $array;
                    header('location: home.php');

                } elseif ($hasil_dosen->rowCount() === 1) {
                    $array = array('dosen',$hasil_dosen_row['nip'],$hasil_dosen_row['nama'],$hasil_dosen_row['username'],$hasil_dosen_row['password'],$hasil_dosen_row['email'],$hasil_dosen_row['institusi']);
                    $_SESSION['role'] = $array;
                    header('location: home.php');

                } elseif ($hasil_mahasiswa->rowCount() === 1) {
                    $array = array('mahasiswa',$hasil_mahasiswa_row['npm'],$hasil_mahasiswa_row['nama'],$hasil_mahasiswa_row['username'],$hasil_mahasiswa_row['password'],$hasil_mahasiswa_row['email']);
                    $_SESSION['role'] = $array;
                    header('location: home.php');

                } else {
                    $error = 'Username atau Password Anda Salah';
                    $_SESSION['error'] = $error;
                    header('location: login.php');
                }
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }








    }

    public function logout()
    {
        session_unset();
        session_destroy();
        header('location: login.php');
    }

    /*
     * return PDO
     */
    public function getDb()
    {
        return $this->db;
    }

}

$auth = new auth();

    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        if (!empty($_POST['command']) && $_POST['command'] === 'login')
        {
            $auth->login($_POST['username'],$_POST['pass']);
        } elseif (!empty($_POST['command']) && $_POST['command'] === 'logout')
        {
            $auth->logout();
        }
    }
