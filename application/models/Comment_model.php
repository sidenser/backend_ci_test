<?php

/**
 * Created by PhpStorm.
 * User: mr.incognito
 * Date: 27.01.2020
 * Time: 10:10
 */
class Comment_model extends CI_Emerald_Model
{
    const CLASS_TABLE = 'comment';


    /** @var int */
    protected $user_id;
    /** @var int */
    protected $assign_id;
    /** @var int */
    protected $parent_id;
    /** @var string */
    protected $text;

    /** @var string */
    protected $time_created;
    /** @var string */
    protected $time_updated;

    // generated
    protected $comments;
    protected $likes;
    protected $user;

    /**
     * @return int
     */
    public function get_assign_id(): int
    {
        return $this->assign_id;
    }

    /**
     * @param int $assign_id
     *
     * @return bool
     */
    public function set_assign_id(int $assign_id)
    {
        $this->assign_id = $assign_id;
        return $this->save('assign_id', $assign_id);
    }

    /**
     * @return int
     */
    public function get_parent_id(): ?int
    {
        return $this->parent_id;
    }

    /**
     * @param int $parent_id
     *
     * @return bool
     */
    public function set_parent_id(int $parent_id)
    {
        $this->parent_id = $parent_id;
        return $this->save('parent_id', $parent_id);
    }

    /**
     * @return int
     */
    public function get_user_id(): int
    {
        return $this->user_id;
    }

    /**
     * @param int $user_id
     *
     * @return bool
     */
    public function set_user_id(int $user_id)
    {
        $this->user_id = $user_id;
        return $this->save('user_id', $user_id);
    }


    /**
     * @return string
     */
    public function get_text(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     *
     * @return bool
     */
    public function set_text(string $text)
    {
        $this->text = $text;
        return $this->save('text', $text);
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
    public function set_time_updated(int $time_updated)
    {
        $this->time_updated = $time_updated;
        return $this->save('time_updated', $time_updated);
    }

    /**
     * @return int
     */
    public function get_likes():int
    {
        return $this->likes;
    }

    /**
     * @param int $likes
     * @return bool
     */
    public function set_likes(int $likes):bool
    {
        $this->likes = $likes;
        return  $this->save('likes', $likes);
    }

    /**
     * @return mixed
     */
    public function get_comments()
    {
        return $this->comments;
    }

    /**
     * @return User_model
     */
    public function get_user():User_model
    {
        if (empty($this->user))
        {
            try {
                $this->user = new User_model($this->get_user_id());
            } catch (Exception $exception)
            {
                $this->user = new User_model();
            }
        }
        return $this->user;
    }

    function __construct($id = NULL)
    {
        parent::__construct();

        App::get_ci()->load->model('User_model');


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
     * @param int $assting_id
     * @return self[]
     * @throws Exception
     */
    public static function get_all_by_assign_id(int $assting_id)
    {

        $data = App::get_ci()->s->from(self::CLASS_TABLE)->where(['assign_id' => $assting_id])->orderBy('time_created','ASC')->many();
        $ret = [];
        foreach ($data as $i)
        {
            $ret[] = (new self())->set($i);
        }
        return $ret;
    }

    /**
     * @param self|self[] $data
     * @param string $preparation
     * @return stdClass|stdClass[]
     * @throws Exception
     */
    public static function preparation($data, $preparation = 'default')
    {
        switch ($preparation)
        {
            case 'full_info_one':
                return self::_preparation_full_info_one($data);
            case 'full_info':
                return self::_preparation_full_info($data);
            default:
                throw new Exception('undefined preparation type');
        }
    }


    /**
     * @param Comment_model $data
     * @return stdClass
     * @throws Exception
     */
    private static function _preparation_full_info_one(Comment_model $data):stdClass
    {
        $o = new stdClass();

        $o->id = $data->get_id();
        $o->text = $data->get_text();
        $o->parent_id = $data->get_parent_id();
        $o->likes = $data->get_likes();
        $o->assign_id = $data->get_assign_id();

        $o->user = User_model::preparation($data->get_user(),'main_page');

        $o->time_created = $data->get_time_created();
        $o->time_updated = $data->get_time_updated();

        return $o;
    }
    /**
     * @param self[] $data
     * @return stdClass[]
     */
    private static function _preparation_full_info($data)
    {
        $ret = [];

        foreach ($data as $d){
            $o = new stdClass();

            $o->id = $d->get_id();
            $o->text = $d->get_text();

            $o->user = User_model::preparation($d->get_user(),'main_page');

            $o->likes = $d->get_likes();

            $o->time_created = $d->get_time_created();
            $o->time_updated = $d->get_time_updated();

            $ret[] = $o;
        }


        return $ret;
    }


}
