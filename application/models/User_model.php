<?php

/**
 * Created by PhpStorm.
 * User: mr.incognito
 * Date: 27.01.2020
 * Time: 10:10
 */
class User_model extends CI_Emerald_Model {
    const CLASS_TABLE = 'user';


    /** @var string */
    protected $email;
    /** @var string */
    protected $password;
    /** @var string */
    protected $personaname;
    /** @var string */
    protected $profileurl;
    /** @var string */
    protected $avatarfull;
    /** @var int */
    protected $rights;
    /** @var float */
    protected $wallet_balance;
    /** @var float */
    protected $wallet_total_refilled;
    /** @var float */
    protected $wallet_total_withdrawn;
    /** @var string */
    protected $time_created;
    /** @var string */
    protected $time_updated;
    /** @var int */
    protected $likes;
    
    private static $_current_user;

    /**
     * @return string
     */
    public function get_email(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return bool
     */
    public function set_email(string $email)
    {
        $this->email = $email;
        return $this->save('email', $email);
    }

    /**
     * @return string|null
     */
    public function get_password(): ?string
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return bool
     */
    public function set_password(string $password)
    {
        $this->password = $password;
        return $this->save('password', $password);
    }

    /**
     * @return string
     */
    public function get_personaname(): string
    {
        return $this->personaname;
    }

    /**
     * @param string $personaname
     *
     * @return bool
     */
    public function set_personaname(string $personaname)
    {
        $this->personaname = $personaname;
        return $this->save('personaname', $personaname);
    }

    /**
     * @return string
     */
    public function get_avatarfull(): string
    {
        return $this->avatarfull;
    }

    /**
     * @param string $avatarfull
     *
     * @return bool
     */
    public function set_avatarfull(string $avatarfull)
    {
        $this->avatarfull = $avatarfull;
        return $this->save('avatarfull', $avatarfull);
    }

    /**
     * @return int
     */
    public function get_rights(): int
    {
        return $this->rights;
    }

    /**
     * @param int $rights
     *
     * @return bool
     */
    public function set_rights(int $rights)
    {
        $this->rights = $rights;
        return $this->save('rights', $rights);
    }

    /**
     * @return float
     */
    public function get_wallet_balance(): float
    {
        return $this->wallet_balance;
    }

    /**
     * @param float $wallet_balance
     *
     * @return bool
     */
    public function set_wallet_balance(float $wallet_balance)
    {
        $this->wallet_balance = $wallet_balance;
        return $this->save('wallet_balance', $wallet_balance);
    }

    /**
     * @return float
     */
    public function get_wallet_total_refilled(): float
    {
        return $this->wallet_total_refilled;
    }

    /**
     * @param float $wallet_total_refilled
     *
     * @return bool
     */
    public function set_wallet_total_refilled(float $wallet_total_refilled)
    {
        $this->wallet_total_refilled = $wallet_total_refilled;
        return $this->save('wallet_total_refilled', $wallet_total_refilled);
    }

    /**
     * @return float
     */
    public function get_wallet_total_withdrawn(): float
    {
        return $this->wallet_total_withdrawn;
    }

    /**
     * @param float $wallet_total_withdrawn
     *
     * @return bool
     */
    public function set_wallet_total_withdrawn(float $wallet_total_withdrawn)
    {
        $this->wallet_total_withdrawn = $wallet_total_withdrawn;
        return $this->save('wallet_total_withdrawn', $wallet_total_withdrawn);
    }

    /**
     * @return string
     */
    public function get_time_created(): string
    {
        return $this->time_created;
    }

    /**
     * @param string $time_created
     *
     * @return bool
     */
    public function set_time_created(string $time_created)
    {
        $this->time_created = $time_created;
        return $this->save('time_created', $time_created);
    }

    /**
     * @return string
     */
    public function get_time_updated(): string
    {
        return $this->time_updated;
    }

    /**
     * @param string $time_updated
     *
     * @return bool
     */
    public function set_time_updated(string $time_updated)
    {
        $this->time_updated = $time_updated;
        return $this->save('time_updated', $time_updated);
    }

    /**
     * @param int $likes
     * @return bool
     */
    public function set_likes(int $likes)
    {
        $this->likes = $likes;
        return $this->save('likes', $likes);
    }

    /**
     * @return int
     */
    public function get_likes():int
    {
        return $this->likes;
    }

    function __construct($id = NULL)
    {
        parent::__construct();
        $this->set_id($id);
    }

    public function reload(bool $for_update = FALSE)
    {
        parent::reload($for_update);

        return $this;
    }

    public static function create(array $data)
    {
        App::get_ci()->s->from(self::CLASS_TABLE)->insert($data)->execute();
        return new static(App::get_ci()->s->get_insert_id());
    }

    public function delete()
    {
        $this->is_loaded(TRUE);
        App::get_ci()->s->from(self::CLASS_TABLE)->where(['id' => $this->get_id()])->delete()->execute();
        return (App::get_ci()->s->get_affected_rows() > 0);
    }

    /**
     * @return self[]
     * @throws Exception
     */
    public static function get_all()
    {

        $data = App::get_ci()->s->from(self::CLASS_TABLE)->many();
        $ret = [];
        foreach ($data as $i)
        {
            $ret[] = (new self())->set($i);
        }
        return $ret;
    }


    /**
     * @param User_model|User_model[] $data
     * @param string $preparation
     * @return stdClass|stdClass[]
     * @throws Exception
     */
    public static function preparation($data, $preparation = 'default')
    {
        switch ($preparation)
        {
            case 'main_page':
                return self::_preparation_main_page($data);
            case 'default':
                return self::_preparation_default($data);
            default:
                throw new Exception('undefined preparation type');
        }
    }

    /**
     * @param User_model $data
     * @return stdClass
     */
    private static function _preparation_main_page($data)
    {
        $o = new stdClass();

        $o->id = $data->get_id();

        $o->personaname = $data->get_personaname();
        $o->avatarfull = $data->get_avatarfull();
        $o->wallet_balance = $data->get_wallet_balance();
        $o->likes = $data->get_likes();


        return $o;
    }


    /**
     * @param User_model $data
     * @return stdClass
     */
    private static function _preparation_default($data)
    {
        $o = new stdClass();

        if (!$data->is_loaded())
        {
            $o->id = NULL;
        } else {
            $o->id = $data->get_id();

            $o->personaname = $data->get_personaname();
            $o->avatarfull = $data->get_avatarfull();

            $o->time_created = $data->get_time_created();
            $o->time_updated = $data->get_time_updated();
        }

        return $o;
    }


    /**
     * Getting id from session
     * @return integer|null
     */
    public static function get_session_id(): ?int
    {
        return App::get_ci()->session->userdata('id');
    }

    /**
     * @return bool
     */
    public static function is_logged()
    {
        $steam_id = intval(self::get_session_id());
        return $steam_id > 0;
    }



    /**
     * Returns current user or empty model
     * @return User_model
     */
    public static function get_user()
    {
        if (! is_null(self::$_current_user)) {
            return self::$_current_user;
        }
        if ( ! is_null(self::get_session_id()))
        {
            self::$_current_user = new self(self::get_session_id());
            return self::$_current_user;
        } else
        {
            return new self();
        }
    }

    /**
     * @param $login
     * @param $password
     * @return int|null
     */
    public static function resolve_user_login($login, $password):?self
    {
        $user = App::get_ci()->s
            ->from(self::CLASS_TABLE)
            ->where('email', $login)
            ->one();

        //TODO тут можем проверять хэш ну и т.д и
        // да лишний запрос но хочу спать так что думаю палками бить не будете
        if (!empty($user) && $password == $user['password']) {
            return new self($user['id']);
        } else {
            return null;
        }
    }

    public function add_money(float $sum)
    {
        App::get_ci()->db->trans_begin();

        try
        {
            App::get_ci()->load->model('Transaction_model');
            $this->set_wallet_balance($this->get_wallet_balance() + $sum);
            $this->set_wallet_total_refilled($this->get_wallet_total_refilled() + $sum);

            Transaction_model::in_money($this->get_id(), $sum, 'Add money');

            if (App::get_ci()->db->trans_status() === FALSE)
            {
                App::get_ci()->db->trans_rollback();
            }
            else
            {
                App::get_ci()->db->trans_commit();
            }

        }catch (Exception $emeraldModelSaveException)
        {
            App::get_ci()->db->trans_rollback();
            throw new RuntimeException();
        }
    }

    public function buy_boosterpack(int $boosterpack_id):int
    {
        App::get_ci()->db->trans_begin();

        try {
            App::get_ci()->load->model('Transaction_model');
            App::get_ci()->load->model('Boosterpack_model');
            App::get_ci()->load->model('User_Boosterpack_model');

            $boosterpack_model = new Boosterpack_model($boosterpack_id);
            $amount = $boosterpack_model->buy();

            $this->set_wallet_balance($this->get_wallet_balance() - $boosterpack_model->get_price());
            $this->set_wallet_total_withdrawn($this->get_wallet_total_withdrawn() + $boosterpack_model->get_price());
            $this->set_likes($amount);
            $comment = sprintf('Buy Boosterpack #%d', $boosterpack_id);

            Transaction_model::out_money($this->get_id(), $boosterpack_model->get_price(), $comment);

            User_Boosterpack_model::create([
                'user_id' => $this->get_id(),
                'boosterpack_id' => $boosterpack_id,
                'count_like' => $amount
            ]);

            //TODO не лучший варик, в идеале добавить кастомный эксепшин
            if ($this->get_wallet_balance() < 0) {
                throw new RuntimeException();
            }

            if (App::get_ci()->db->trans_status() === FALSE) {
                App::get_ci()->db->trans_rollback();
            } else {
                App::get_ci()->db->trans_commit();
            }
            return $amount;

        } catch (Exception $emeraldModelSaveException) {
            App::get_ci()->db->trans_rollback();
            throw new RuntimeException($emeraldModelSaveException->getMessage());
        }
    }

    /**
     * @param int $post_id
     */
    public function like_post(int $post_id)
    {
        App::get_ci()->db->trans_begin();

        try
        {
            App::get_ci()->load->model('Post_like_model');
            App::get_ci()->load->model('Post_model');
            $this->set_likes($this->get_likes() - 1);

            Post_like_model::create([
                'user_id' => $this->get_id(),
                'post_id' => $post_id
            ]);

            $post_model = new Post_model($post_id);
            $post_model->set_likes($post_model->get_likes() + 1);

            if (App::get_ci()->db->trans_status() === FALSE)
            {
                App::get_ci()->db->trans_rollback();
            }
            else
            {
                App::get_ci()->db->trans_commit();
            }

            return $post_model->get_likes();

        }catch (Exception $ex)
        {
            App::get_ci()->db->trans_rollback();
            throw new RuntimeException($ex->getMessage());
        }
    }

    /**
     * @param int $comment_id
     */
    public function like_comment(int $comment_id)
    {
        App::get_ci()->db->trans_begin();

        try
        {
            App::get_ci()->load->model('Comment_like_model');
            App::get_ci()->load->model('Comment_model');
            $this->set_likes($this->get_likes() - 1);

            Comment_Like_model::create([
                'user_id' => $this->get_id(),
                'comment_id' => $comment_id
            ]);

            $comment_model = new Comment_model($comment_id);
            $comment_model->set_likes($comment_model->get_likes() + 1);

            if (App::get_ci()->db->trans_status() === FALSE)
            {
                App::get_ci()->db->trans_rollback();
            }
            else
            {
                App::get_ci()->db->trans_commit();
            }

            return $comment_model->get_likes();

        }catch (Exception $ex)
        {
            App::get_ci()->db->trans_rollback();
            throw new RuntimeException();
        }
    }

}
