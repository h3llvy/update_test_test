<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class User
{
    private $name;
    private $key;
    private $id;
    private $photo;
    private $email;
    private $hash_pass;
    private $state;
    private $time_end;
    private $code;

    public function __construct($email__str, $hash_pass__str, $name__str = "-", $key__int = "NULL", $id__str = "NULL",
                                $photo__str = 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTv3qqRlHAoe5yTI7qTfZrCEr4SurL9lUIqNQ&usqp=CAU',
                                $state__int = 0, $time_end__int = "NULL", $code__int = "NULL")
    {
        $this->name = $name__str;
        $this->key = (int)$key__int;
        $this->id = $id__str;
        $this->photo = $photo__str;
        $this->email = $email__str;
        $this->hash_pass = $hash_pass__str;
        $this->state = (int)$state__int;
        $this->time_end = (int)$time_end__int;
        $this->code = (int)$code__int;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getHashPass()
    {
        return $this->hash_pass;
    }

    public function getTimeEnd()
    {
        return $this->time_end;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPhoto()
    {
        return $this->photo;
    }

    public function getState()
    {
        return $this->state;
    }

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setHashPass($hash_pass)
    {
        $this->hash_pass = $hash_pass;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setKey($key)
    {
        $this->key = $key;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setPhoto($photo)
    {
        $this->photo = $photo;
    }

    public function setState($state)
    {
        $this->state = $state;
    }

    public function setTimeEnd($time_end)
    {
        $this->time_end = $time_end;
    }
}

class DB
{
    private $servernameDB = 'remotemysql.com:3306';
    private $usernameDB = 'RmIITGExF2';
    private $passwordDB = 'zDL2VdvFnP';
    private $nameDB = 'RmIITGExF2';
    private $conn;

    public function __construct()
    {
        $this->conn = new mysqli($this->servernameDB, $this->usernameDB, $this->passwordDB, $this->nameDB);
        if ($this->conn->connect_error)
            die("Connection failed: " . $this->conn->connect_error);
    }

    public function getConn()
    {
        return $this->conn;
    }
}

class QueryManagerDB extends DB
{
    protected $res;

    public function __construct($sql)
    {
        parent::__construct();
        if ($sql != null) {
            $this->res = $this->getConn()->query($sql);
        }
    }
}

class UserSearcher extends QueryManagerDB
{

    public function __construct($sql)
    {
        parent::__construct($sql);
    }

    public function getUserByRow($row)
    {
        if ($this->res->num_rows)
            return new User($row['email'], $row['pass'], $row['name'], $row['key'], $row['id'], $row['photo'],
                $row['state'], $row['time_end'], $row['code']);
        else return NULL;
    }

    public function get()
    {
        throw new Exception("Not realesed method");
    }
}

class UserSeacherAll extends UserSearcher
{
    protected $users;

    public function __construct($sort = "key")
    {
        $this->users = [];
        parent::__construct('SELECT * FROM user ORDER BY `' . $sort . '`');
        while ($row = $this->res->fetch_array()) {
            $this->users[] = $this->getUserByRow($row);
        }
    }

    public function get()
    {
        return $this->users;
    }
}

class UserSearcherByKey extends UserSearcher
{
    public function __construct($key__int)
    {
        parent::__construct(sprintf("SELECT * FROM user WHERE `key`=%d", $key__int));
    }

    public function get()
    {
        return $this->getUserByRow($this->res->fetch_array());
    }
}

class UserSearcherByEmail extends UserSearcher
{
    public function __construct($email__str)
    {
        parent::__construct(sprintf("SELECT * FROM user WHERE email=\"%s\"", $email__str));
    }

    public function get()
    {
        return $this->getUserByRow($this->res->fetch_array());
    }
}

class UserSearcherById extends UserSearcher
{
    public function __construct($id__str)
    {
        parent::__construct(sprintf("SELECT * FROM user WHERE id=\"%s\"", $id__str));
    }

    public function get()
    {
        return $this->getUserByRow($this->res->fetch_array());
    }
}

class UserSetter extends QueryManagerDB
{
    public function __construct($sql)
    {
        parent::__construct($sql);
    }
}

class UserSetterNew extends UserSetter
{
    public function __construct($user)
    {
        $sql = sprintf("INSERT INTO user (email, pass, name, id, photo, state, time_end,
 code) 
 VALUES (\"%s\",\"%s\",\"%s\",%s,\"%s\",%d,%d,%d)",
            $user->getEmail(), $user->getHashPass(), $user->getName(),
            ($user->getId() === "NULL") ? ("NULL") : ("\"" . $user->getId() . "\""), $user->getPhoto(),
            $user->getState(), $user->getTimeEnd(), $user->getCode()
        );

        parent::__construct($sql);
    }
}

class UserSetterByKey extends UserSetter
{
    public function __construct($user)
    {

        $sql = sprintf("UPDATE user 
SET email=\"%s\", pass=\"%s\", name=\"%s\", id=%s, photo=\"%s\", state=%d, time_end=%d, code = %d 
 WHERE `key` = %d",
            $user->getEmail(), $user->getHashPass(), $user->getName(),
            ($user->getId() === "NULL") ? ("NULL") : ("\"" . $user->getId() . "\""), $user->getPhoto(),
            $user->getState(), $user->getTimeEnd(), $user->getCode(), $user->getKey()
        );

        parent::__construct($sql);
    }
}

class ManagerCookie
{
    protected $user;
    protected $json;

    public function __construct($getHtml__bool = true)
    {
        $this->json = ["alert" => null, "main" => null, "nav" => null, 'recaptcha' => null];
        if (isset($_COOKIE['id'])) {
            $id = $_COOKIE['id'];
            $finder = new UserSearcherById($id);
            $this->user = $finder->get();
            if (!$this->user) {
                $this->deleteCookie();
            } else if ($getHtml__bool) {
                $this->getHtmlSelfPage();
                exit();
            }
        }
    }

    protected function addJson($key, $value)
    {
        $this->json[$key] .= $value . "\n";
    }

    protected function getHtmlSelfPage()
    {
        if ($this->getUser()) {
            echo preg_replace("!\r\n!", "", json_encode(["main" => "
            <article>
            <div class='profile'>
         
            <img class='__photo' height='400px' width='=400px' src =\"{$this->user->getPhoto()}\">
    <div class='info_user'>
    <ul class=\"info\">
        <li>            Имя        </li>
        <li>            email        </li>
        <li>            id        </li>
        <li>            Статус            </li>
    </ul>
    <ul class=\"info right\">
        <li>            {$this->user->getName()}        </li>
        <li>            {$this->user->getEmail()}        </li>
        <li>            {$this->user->getKey()}        </li>
        <li>" . ($this->user->getState() ? "Почта активирована" : "Почта не активирована") . "</li>
    </ul>
    </div>
            </article>         
            ", "nav" => "
<button class=\"__signOut\">Выйти</button>
<button class=\"__settings\">Настройки</button>
<button class=\"__users\">Пользователи</button>            
                        "]));
        } else if (!$this->getUser()) {
            echo preg_replace("!\r\n!", "", json_encode(["main" => "<article>
        <div>
            <label>E-mail</label>
            <input class=\"__email\" type=\"text\">
        </div>
        <div>
            <label>Password</label>
            <input class=\"__password\" type=\"password\"></div>
            <div id=\"recaptcha\"></div>
        <button class=\"__signInToServer\">Войти</button>
            </article>  
", "nav" => "
<button class=\"__signUp\">Регистрация</button>
<button class=\"__signIn\">Вход</button>
", "recaptcha" => 1]));
        }
    }

    protected function setCookie()
    {
//        echo '<br>COOKIESET<br><br>';
        $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
        setcookie('id', $this->user->getId(), time() + 60 * 60 * 24 * 365, '/', $domain, false);
    }

    protected function deleteCookie()
    {
        $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
        setcookie('id', null, -1, '/', $domain, false);
        $_COOKIE['id'] = null;
    }

    public function createId()
    {
        $this->user->setId(md5(uniqid()));
    }

    public function getUser()
    {
        return $this->user;
    }
}

class SenderMailCode
{


    public function __construct($user)
    {
        require $_SERVER['DOCUMENT_ROOT'] . '/mail/Exception.php';
        require $_SERVER['DOCUMENT_ROOT'] . '/mail/PHPMailer.php';
        require $_SERVER['DOCUMENT_ROOT'] . '/mail/SMTP.php';

        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->SMTPDebug = 2; // 0 = off (for production use) - 1 = client messages - 2 = client and server messages
        $mail->Host = "smtp.gmail.com"; // use $mail->Host = gethostbyname('smtp.gmail.com'); // if your network does not support SMTP over IPv6
        $mail->Port = 587; // TLS only
        $mail->SMTPSecure = 'tls'; // ssl is deprecated
        $mail->SMTPAuth = true;
        $mail->Username = 'h3llvy@gmail.com'; // email
        $mail->Password = 'qw#rty1448'; // password
        $mail->setFrom('h3llvy@gmail.com', 'BOT'); // From email and name
        $mail->addAddress($user->getEmail(), $user->getName()); // to email and name
        $mail->Subject = 'Code Activation';
        $mail->msgHTML("Code activation available for 1 hour: {$user->getCode()}"); //$mail->msgHTML(file_get_contents('contents.html'), __DIR__); //Read an HTML message body from an external file, convert referenced images to embedded,
        $mail->AltBody = 'HTML messaging not supported'; // If html emails is not supported by the receiver, show this body
// $mail->addAttachment('images/phpmailer_mini.png'); //Attach an image file
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        if (!$mail->send()) {
            echo "Mailer Error: " . $mail->ErrorInfo;
        } else {
            echo "Message sent!";
        }
    }

}

class ManagerPostRequests extends ManagerCookie
{
    private $access = 0;

//    private $secretKey = "6LcXdm8bAAAAAOy22AVh1199MLz76Kr-t33GXPXv";

    public function __construct($bool = true)
    {
        parent::__construct($bool);
        if (($_POST['captcha'])) {
            $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LcXdm8bAAAAAOy22AVh1199MLz76Kr-t33GXPXv&response=" . $_POST['captcha']);
            $captcha_success = json_decode($verify);
            $this->access = $captcha_success->success;
        }
        if (!$this->access) {
            $this->addJson('alert', "Неверная капча");
            echo json_encode($this->json);
            exit();
        }
    }

    public function getAccess()
    {
        return $this->access;
    }
}

class ManagerPostRequestsSignUp extends ManagerPostRequests
{
    public function __construct()
    {
        parent::__construct(false);
        $this->user = new User($_POST['email'], md5($_POST['pass']));
        $finder = new UserSearcherByEmail($this->user->getEmail());
        if ($finder->get()) {
            $this->addJson('alert', 'Почта уже зарегистрирована');
            echo json_encode($this->json);
        } else {
            $this->createId();
            $setterByKey = new UserSetterNew($this->user);
            $this->setCookie();
            $this->getHtmlSelfPage();

        }
    }
}

class ManagerPostRequestsSignIn extends ManagerPostRequests
{
    private $email;
    private $pass;

    public function __construct()
    {
        parent::__construct(false);
        $this->email = $_POST['email'];
        $this->pass = $_POST['pass'];
        $finder = new UserSearcherByEmail($this->email);
        $this->user = $finder->get();
        if (($this->user->getHashPass() != md5($this->pass))) {
            $this->addJson('alert', 'Неверная почта или пароль');
            echo json_encode($this->json);
        } else {
            $this->createId();
            $setterByKey = new UserSetterByKey($this->user);
            $this->setCookie();
        }
    }


}

class ManagerGetRequestsSelfOrFormIn extends ManagerCookie
{

    public function __construct()
    {
        parent::__construct();
        $this->getHtmlSelfPage();
    }

    public function getId()
    {
        return $this->id;
    }

    public function createId()
    {
        $this->id = md5(uniqid());
    }
}

class ManagerGetRequestsOut extends ManagerCookie
{
    public function __construct()
    {
        $this->deleteCookie();
        $this->getHtmlSelfPage();
    }
}

class ManagerPostRequestsSaveSetting extends ManagerPostRequests
{
    public function __construct()
    {
        parent::__construct(false);
        if ($this->user) {
            if ($_POST['name'])
                $this->user->setName($_POST['name']);
            if ($_POST['photo'])
                $this->user->setPhoto($_POST['photo']);
            if ($_POST['old_pass'])
                if ($this->user->getHashPass() == md5($_POST['old_pass'])) {
                    $this->user->setHashPass(md5($_POST['curr_pass']));
                } else  $this->addJson('alert', "Неверный старый пароль");
            $setterNewUser = new UserSetterByKey($this->user);
            $this->addJson('alert', "Настройки сохранены");
        } else         $this->addJson('alert', 'Вы не авторизированы');
        echo json_encode($this->json);
    }
}

class ManagerGetRequestsFormSignUp extends ManagerCookie
{
    public function __construct()
    {
        parent::__construct();
        echo json_encode(["main" => "<article>
        <div>
            <label>E-mail</label>
            <input class=\"__email\" type=\"text\">
        </div>
        <div>
            <label>Password</label>
            <input class=\"__password\" type=\"password\"></div>
        <div>
            <label>Password verification</label>
            <input class=\"__pass_verif\" type=\"password\"></div>
            <div id=\"recaptcha\"></div>
        <button class=\"__reg\">Регистрация</button>
            </article>  
", "nav" => "
<button class=\"__signUp\">Регистрация</button>
<button class=\"__signIn\">Вход</button>
", "recaptcha" => 1]);
    }
}

class ManagerGetRequestsFormSettings extends ManagerCookie
{
    public function __construct()
    {
        parent::__construct(false);
        if ($this->user) {
            echo json_encode(['main' => '
            <article>' . ((!$this->user->getState()) ?
                    '<div class="__active_email">У вас неактивированная почта! Нажмите сюда, чтобы это исправить и получить все возможности! </div>
                    ' : '
    <div class="__active_email green">Почта активирована! </div>')
                . '
            <div class="__labels">
            <div>
                <label>Имя</label>
                <input class="__name" type="text">
            </div>
            <div>
                <label>URL картинки</label>
                <input class="__img" type="text">

            </div>
            <div>
                <label>Старый пароль</label>
                <input class="__oldPassword" type="password"></div>
            <div>
                <label>Новый пароль</label>
                <input class="__curPassword" type="password"></div>
            <div>
                <label>Подтвердите пароль</label>
                <input class="__verification" type="password"></div>
                 <div id="recaptcha"></div>
            <button class="__saveSettings" >Сохранить</button>
            </div>
        </article>       
            ', 'recaptcha' => 1]);

        } else echo json_encode(['main' => 'Вы не авторизованы!', 'nav' => '
<button class="__signUp">Регистрация</button>
<button class="__signIn">Вход</button>
        ']);
    }

}

class ManagerGetRequestsViewAllUsers extends ManagerCookie
{
    public function __construct()
    {
        parent::__construct(false);
        if ($this->user) {
            if ($this->user->getState()) {
                $html = '    
    <article id="users" >
        <table id="users" class="ui-widget ui-widget-content">
            <thead>
            <tr class="ui-widget-header ">
                <th id="key">ID</th>
                <th id="photo">Ава</th>
                <th id="name">Имя</th>
                <th id="email">Почта</th>
                <th id="state">Статус</th>
            </tr>
            </thead>
            <tbody>';
                if (isset($_GET['sort'])) {
                    $finderAllUsers = new UserSeacherAll($_GET['sort']);
                } else {
                    $finderAllUsers = new UserSeacherAll();
                }
                foreach ($finderAllUsers->get() as $user) {
                    $html .= '<tr class="row_user">
                <td class="__key">' . $user->getKey() . '</td>
                <td><img height="100px" width="100px" src="' . $user->getPhoto() . '"></td>
                <td>' . $user->getName() . '</td>
                <td>' . $user->getEmail() . '</td>
                <td>' . $user->getState() . '</td>
            </tr>';
                }
                $html .= '</tbody>
        </table>
    </article>  
  ';
                $this->addJson('main', $html);
                echo json_encode($this->json);
            }
        }
    }
}

class ManagerGetRequestsViewUser extends ManagerCookie
{
    public function __construct()
    {
        parent::__construct(false);
        if ($this->user)
            if ($this->user->getState()) {
                $finderByKey = new UserSearcherByKey((int)$_GET['key']);
                $this->user = $finderByKey->get();
                $this->getHtmlSelfPage();

            }
    }
}

class ManagerPostRequestsActiveEmailByCode extends ManagerPostRequests
{
    public function __construct()
    {
        parent::__construct(false);
        if ($this->user) {
            if (($_POST['code'])) {
                if (($_POST['code'] == $this->user->getCode()) and (time() <= (int)$this->user->getTimeEnd())) {
                    $this->user->setState(1);
                    $setterByKey = new UserSetterByKey($this->user);
                } else {
                    $this->addJson('alert', 'Неверный код');
                }
            } else $this->addJson('alert', 'Неверный код');
        } else
            $this->addJson('alert', 'Неверный код');
        echo json_encode($this->json);
    }
}

class ManagerGetRequestsSendCode extends ManagerCookie
{
    public function __construct()
    {
        parent::__construct(false);
        if ($this->user) {
            $this->user->setTimeEnd(time() + 3600);
            $this->user->setCode(rand(100000, 999999));
            $setterUserByKey = new UserSetterByKey($this->user);
            $senderCode = new SenderMailCode($this->user);
        }
    }
}

class ManagerExternalSoures
{
    private $method;
    private $action;
    private $manReq;

    public function __construct()
    {
        if (count($_GET) && count($_POST)) {
            $this->method = NULL;

        } else if (count($_GET) or !count($_POST)) {
            $this->method = 'GET';
            $this->action = $_GET['action'];
            switch ($this->action) {
                case 'out':
                    $this->manReq = new ManagerGetRequestsOut();
                    break;
                case 'view_all_users':
                    $this->manReq = new ManagerGetRequestsViewAllUsers();
                    break;
                case 'form_settings':
                    $this->manReq = new ManagerGetRequestsFormSettings();
                    break;
                case 'view_user':
                    $this->manReq = new ManagerGetRequestsViewUser();
                    break;
                case 'send_code':
                    $this->manReq = new ManagerGetRequestsSendCode();
                    break;
                case 'form_up':
                    $this->manReq = new ManagerGetRequestsFormSignUp();
                    break;

                default:
                    $this->manReq = new ManagerGetRequestsSelfOrFormIn();
                    break;
            }
        } else if (count($_POST)) {
            $this->method = 'POST';
            $this->action = $_POST['action'];
            switch ($this->action) {
                case 'in':
                    $this->manReq = new ManagerPostRequestsSignIn();
                    break;
                case 'up':
                    $this->manReq = new ManagerPostRequestsSignUp();
                    break;
                case 'save_settings':
                    $this->manReq = new ManagerPostRequestsSaveSetting();
                    break;
                case 'active_code':
                    $this->manReq = new ManagerPostRequestsActiveEmailByCode();
                    break;
                case 'view_user':
                    if ($_GET['key'])
                        $this->manReq = new ManagerGetRequestsViewUser();
                    break;
            }
        }

        function getMethod()
        {
            return $this->method;
        }

        function getManReq()
        {
            return $this->manReq;
        }
    }
}


$manager = new ManagerExternalSoures();
?>