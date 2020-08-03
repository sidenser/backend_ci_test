<?php

/**
 * Created by PhpStorm.
 * User: mr.incognito
 * Date: 27.01.2020
 * Time: 10:10
 */
class User_Boosterpack_model extends CI_Emerald_Model
{
    const CLASS_TABLE = 'user_boosterpack';

    //TODO вот тут не совсем уверен, нужно больше информации
    // (если цена на бустер изменется то будет не очень, но как вариант цена может не изменяться а просто создаваться новая
    // в бустер может содержать еще дополнительную информация так что оставим так суть я думаю изложил

    /** @var int */
    protected $user_id;
    /** @var int */
    protected $boosterpack_id;
    /** @var int */
    protected $count_like;
    /** @var string */
    protected $time_created;

    /**
     * @param int $user_id
     * @return bool
     */
    public function set_user_id(int $user_id)
    {
        $this->user_id = $user_id;
        return $this->save('user_id', $user_id);
    }

    /**
     * @return int
     */
    public function get_user_id():int
    {
        return $this->user_id;
    }

    /**
     * @param int $boosterpack_id
     * @return bool
     */
    public function set_boosterpack_id(int $boosterpack_id)
    {
        $this->boosterpack_id = $boosterpack_id;
        return $this->save('boosterpack_id', $boosterpack_id);
    }

    /**
     * @return int
     */
    public function get_boosterpack_id():int
    {
        return $this->boosterpack_id;
    }

    /**
     * @param int $count_like
     * @return bool
     */
    public function set_count_like(int $count_like)
    {
        $this->count_like = $count_like;
        return $this->save('count_like', $count_like);
    }

    /**
     * @return int
     */
    public function get_count_like():int
    {
        return $this->count_like;
    }

    /**
     * @param string $time_created
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
    public function get_time_created():string
    {
        return  $this->time_created;
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

}
