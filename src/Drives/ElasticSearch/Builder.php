<?php

namespace CrCms\Repository\Drives\ElasticSearch;

/**
 * Class Builder
 *
 * @package CrCms
 */
class Builder
{

    public $bindings = [
        'select' => [],
        'join'   => [],
        'where'  => [],
        'having' => [],
        'order'  => [],
        'union'  => [],
    ];

    public $wheres = [];

    public $operators = [
        '='=>'eq',
        '>'=>'gt',
        '>='=>'gte',
        '<'=>'lt',
        '<='=>'lte',
//        '=', '<', '>', '<=', '>=', '<>', '!=',
//        'like', 'like binary', 'not like', 'between', 'ilike',
//        '&', '|', '^', '<<', '>>',
//        'rlike', 'regexp', 'not regexp',
//        '~', '~*', '!~', '!~*', 'similar to',
//        'not similar to', 'not ilike', '~~*', '!~~*',
    ];

    public $columns = [];

    public $size = 0;

    public function select(array $columns)
    {
        $this->columns = is_array($columns) ? $columns : func_get_args();

        return $this;
    }


    public function whereMatch($field,$value,$boolean = 'and')
    {
        return $this->where($field,'=',$value,'match',$boolean);
    }

    public function orWhereMatch($field,$value,$boolean = 'and')
    {
        return $this->whereMatch($field,$value,$boolean);
    }


    public function whereTerm($field,$value,$boolean = 'and')
    {
        return $this->where($field,'=',$value,'term',$boolean);
    }

    public function orWhereTerm($field,$value,$boolean = 'or')
    {
        return $this->whereTerm($field,$value,$boolean);
    }


    public function whereRange($field,$operator = null,$value = null,$boolean = 'and')
    {
        return $this->where($field,$operator,$value,'range',$boolean);
    }

    public function orWhereRange($field,$operator = null,$value = null)
    {
        return $this->where($field,$operator,$value,'or');
    }

    public function whereBetween($field,array $values,$boolean = 'and')
    {
        return $this->where($field,'=',$values,'range',$boolean);
    }

    public function orWhereBetween($field,array $values)
    {
        return $this->whereBetween($field,$values,'or');
    }

    public function where($field,$operator = null,$value = null,$leaf = 'term',$boolean = 'and')
    {

        if ($field instanceof \Closure) {
            return $this->whereNested($field,$boolean);
        }

        if (func_num_args() == 2) {
            list($value, $operator) = [$operator,'='];
        }


        $type = 'Basic';
        $column = $field;



        $this->wheres[] = compact(
            'type', 'column','leaf' ,'value', 'boolean','operator'
        );

        return $this;
    }

    public function orWhere($field,$operator = null,$value = null,$leaf = 'term')
    {
        return $this->where($field,$operator,$value,$leaf,'or');
    }

    public function whereNested(\Closure $callback,$boolean)
    {
        $query = $this->newQuery();

        call_user_func($callback, $query);

        return $this->addNestedWhereQuery($query, $boolean);
    }

    public function addNestedWhereQuery($query, $boolean = 'and')
    {
        if (count($query->wheres)) {
            $type = 'Nested';

            $this->wheres[] = compact('type', 'query', 'boolean');
        }

        return $this;
    }

    public function newQuery()
    {
        return new static();
    }

    protected $bool = [];

    protected function resolveWhere(self $object,$result = [])
    {$must = $should = [];
        foreach ($object->wheres as $where) {


//            if ($where['type'] === 'Basic') {
//                if ($where['boolean'] === 'and') {
//                    $result['must'][] = [$where['leaf'] => [$where['column'] => $where['value']]];
//                } elseif ($where['boolean'] === 'or') {
//                    $result['should'][] = [$where['leaf']=>[$where['column'] => $where['value']]] ;
//                }
//            } else {
//                /*if ($where['boolean'] === 'and') {
//                    $result = $this->resolveWhere($where['query'],$result);
//                } else {
//                    $result = $this->resolveWhere($where['query'],$result);
//                }*/
//                //$result['bool'] = $this->resolveWhere($where['query'],$result);
//                $result[] = $this->resolveWhere($where['query'],$result);
//            }



            if ($where['type'] === 'Basic') {
                if ($where['boolean'] === 'and') {
                    $must[] = [$where['leaf'] => [$where['column'] => $where['value']]];
                } elseif ($where['boolean'] === 'or') {
                    $should[] = [$where['leaf']=>[$where['column'] => $where['value']]] ;
                }
            } else {

                if ($where['boolean'] === 'and') {
                    $must[] = $this->resolveWhere($where['query'],$result);
                } else {
                    $should[] = $this->resolveWhere($where['query'],$result);
                }
            }


        }

//        return $result;
//        dump($result);
        $bool = [];
        if (!empty($must)) {
            $bool['must'] = $must;
        }
        if (!empty($should)) {
            $bool['should'] = $should;
        }
        return ['bool'=>
            $bool
        ];
    }


    public function get()
    {
        $a = $this->resolveWhere($this);

//$a = $this->resolveBool($a);

        echo json_encode($a);exit();
    }


    protected function resolveBool(array $bool,$newBool = [])
    {
        foreach ($bool as $item) {

            if (isset($item['bool'])) {
                $newBool = $this->resolveBool($item['bool'],$newBool);
            }

            if (isset($item['should'])) {
                $newBool['should'] = $item['should'];
            }

            if (isset($item['must'])) {
                $newBool['should'] = array_merge([['bool'=>['must'=>$item['must']]]],$newBool['should']);
                //$newBool['should'] = array_merge($item['must'],$newBool['should']);
            }
        }

        return $newBool;
    }


}