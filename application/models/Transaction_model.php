<?php

/**
 * Created by PhpStorm.
 * User: mr.incognito
 * Date: 27.01.2020
 * Time: 10:10
 */
class Transaction_model extends CI_Emerald_Model
{
    const CLASS_TABLE = 'transaction';

    //TODO сильно не усложнял просто списание суммы в комментах куда деньги)
    //TODO Cуть и так понятна если что тут нужно смотреть по ситуации и  на будуще можно добавить операции и т.д

    /** @var int */
    protected $user_id;
    /** @var float*/
    protected $amount;
    /** @var string */
    protected $comment;
    /** @var string */
    protected $time_created;


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
     * @param float $amount
     * @return bool
     */
    public function set_amount(float $amount)
    {
        $this->amount = $amount;
        return $this->save('amount', $amount);
    }

    /**
     * @return float
     */
    public function get_amount():float
    {
        return $this->amount;
    }

    /**
     * @param string $comment
     */
    public function set_comment(string $comment)
    {
        $this->comment = $comment;
        $this->save('comment', $comment);
    }

    /**
     * @return string
     */
    public function get_comment():?string
    {
        return $this->comment;
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

    //TODO для этой задачи этих методов можно и не создавать
    // но пусть будут операция с деньгами зачастую много где используется

    public static function in_money(int $user_id, float $amount, $comment = ''):self
    {
        $trn = self::create([
            'user_id' => $user_id,
            'amount' => $amount,
            'comment' => $comment, // комент для инфы откуда деньги пришли (лайт версия)
        ]);

        return $trn;
    }

    //TODO по хорошому тут должен быть еще тип транзакции...
    public static function out_money(int $user_id, float $amount, $comment = ''):self
    {
        $trn = self::create([
            'user_id' => $user_id,
            'amount' => -$amount,
            'comment' => $comment, // комент для инфы откуда деньги пришли (лайт версия)
        ]);

        return $trn;
    }

}
