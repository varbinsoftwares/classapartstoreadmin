<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Product_model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->database();
    }

    function edit_table_information($tableName, $id) {
        $this->User_model->tracking_data_insert($tableName, $id, 'update');
        $this->db->update($tableName, $id);
    }

    public function query_exe($query) {
        $query = $this->db->query($query);
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[] = $row;
            }
            return $data; //format the array into json data
        }
    }

    function delete_table_information($tableName, $columnName, $id) {
        $this->db->where($columnName, $id);
        $this->db->delete($tableName);
    }

    ///*******  Get data for deepth of the array  ********///

    function get_children($id, $container) {
        $this->db->where('id', $id);
        $query = $this->db->get('category');
        $category = $query->result_array()[0];
        $this->db->where('parent_id', $id);
        $query = $this->db->get('category');
        if ($query->num_rows() > 0) {
            $childrens = $query->result_array();

            $category['children'] = $query->result_array();

            foreach ($query->result_array() as $row) {
                $pid = $row['id'];
                $this->get_children($pid, $container);
            }

            print_r($category);
            return $category;
        } else {
            
        }
    }

    function getparent($id, $texts) {
        $this->db->where('id', $id);
        $query = $this->db->get('category');
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                array_push($texts, $row);
                $texts = $this->getparent($row['parent_id'], $texts);
            }
            return $texts;
        } else {
            return $texts; //format the array into json data
        }
    }

    function parent_get($id) {
        $catarray = $this->getparent($id, []);
        array_reverse($catarray);
        $catarray = array_reverse($catarray, $preserve_keys = FALSE);
        $catcontain = array();
        foreach ($catarray as $key => $value) {
            array_push($catcontain, $value['category_name']);
        }
        $catstring = implode("->", $catcontain);
        return array('category_string' => $catstring, "category_array" => $catarray);
    }

    function child($id) {
        $this->db->where('parent_id', $id);
        $query = $this->db->get('category');
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {

                $cat[] = $row;
                $cat[$row['id']] = $this->child($row['id']);
                $cat[] = $row;
            }

            return $cat; //format the array into json data
        }
    }

    function singleProductAttrs($product_id) {
        $query = "SELECT pa.attribute, pa.product_id, pa.attribute_value_id, cav.attribute_value FROM product_attribute as pa 
join category_attribute_value as cav on cav.id = pa.attribute_value_id
where pa.product_id = $product_id group by attribute_value_id";
        $product_attr_value = $this->query_exe($query);
        $arrayattr = [];
        foreach ($product_attr_value as $key => $value) {
            $attrk = $value['attribute'];
            $attrv = $value['attribute_value'];
            array_push($arrayattr, $attrk . '-' . $attrv);
        }
        return implode(", ", $arrayattr);
    }

    function product_attribute_list($product_id) {
        $this->db->where('product_id', $product_id);
        $this->db->group_by('attribute_value_id');
        $query = $this->db->get('product_attribute');
        $atterarray = array();
        if ($query->num_rows() > 0) {
            $attrs = $query->result_array();
            foreach ($attrs as $key => $value) {
                $atterarray[$value['attribute_id']] = $value;
            }
            return $atterarray;
        } else {
            return array();
        }
    }

    function productAttributes($product_id) {
        $pquery = "SELECT pa.attribute, cav.attribute_value FROM product_attribute as pa
      join category_attribute_value as cav on cav.id = pa.attribute_value_id
      where pa.product_id = $product_id";
        $attr_products = $this->query_exe($pquery);
        return $attr_products;
    }

    function variant_product_attr($product_id) {
        $queryr = "SELECT pa.attribute_id, pa.attribute, pa.product_id, pa.attribute_value_id, cav.attribute_value FROM product_attribute as pa 
join category_attribute_value as cav on cav.id = pa.attribute_value_id 
where pa.product_id=$product_id ";
        $query = $this->db->query($queryr);
        return $query->result_array();
    }

    function category_attribute_list($id) {
        $this->db->where('attribute_id', $id);
        $this->db->group_by('attribute_value');
        $query = $this->db->get('category_attribute_value');
        if ($query->num_rows() > 0) {
            $attrs = $query->result_array();
            return $attrs;
        } else {
            return array();
        }
    }
    
    
    
    
    

}

class JsonSorting {

    public function __construct($source) {
        $this->source = $source;
    }

    public function count_values($keyname, $keyval) {
        $count = 0;
        foreach ($this->source as $key => $value) {
            if ($keyval == $value[$keyname]) {
                $count++;
            }
        }
        return [$keyval, $count];
    }

    public function collect_data($keyname) {
        $datalist = array();
        $ll2 = array();
        $count1 = 0;
        foreach ($this->source as $key => $value) {
            $temp = $value[$keyname];
            if (in_array($temp, $datalist)) {
                
            } else {
                array_push($datalist, $temp);
            }
        }
        foreach ($datalist as $key => $value) {
            $temp1 = $this->count_values($keyname, $value);
            $ll2[$temp1[0]] = $temp1[1];
        }
        return $ll2;
    }

    public function data_combination($keyname1, $keyname2) {
        $data_contain = array();
        $key_1 = $this->collect_data($keyname1);
        $key_2 = $this->collect_data($keyname2);
        $key_data1 = array_keys($key_1);
        $key_data2 = array_keys($key_2);
        foreach ($key_data2 as $kd2 => $vl2) {
            $sort_temp = array();
            foreach ($key_data1 as $kd1 => $vl1) {
                $count = 0;
                foreach ($this->source as $kd => $vl) {
                    $temp1 = $vl[$keyname1];
                    $temp2 = $vl[$keyname2];
                    if ($temp1 == $vl1 && $temp2 == $vl2) {
                        $count++;
                        $sort_temp[$vl1] = $count;
                    }
                }
                $data_contain[$vl2] = $sort_temp;
            }
        }
        return $data_contain;
    }

    public function data_combination_quantity($keyname1, $keyname2) {
        $data_contain = array();
        $key_1 = $this->collect_data($keyname1);
        $key_2 = $this->collect_data($keyname2);
        $key_data1 = array_keys($key_1);
        $key_data2 = array_keys($key_2);
        foreach ($key_data2 as $kd2 => $vl2) {
            $sort_temp = array();
            foreach ($key_data1 as $kd1 => $vl1) {
                $count = 0;
                foreach ($this->source as $kd => $vl) {
                    $temp1 = $vl[$keyname1];
                    $temp2 = $vl[$keyname2];
                    if ($temp1 == $vl1 && $temp2 == $vl2) {
                        $count = $count + $vl1;
                        $sort_temp[$vl1] = $count;
                    }
                }
                $data_contain[$vl2] = $sort_temp;
            }
        }
        $temp = array();
        foreach ($data_contain as $key => $value) {
            $temp2 = array_sum($value);
            $temp[$key] = $temp2;
        }

        return $temp;
    }

}
