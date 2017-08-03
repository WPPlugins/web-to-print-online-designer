<?php
if ( ! defined( 'ABSPATH' ) ) exit;
class Nbdesigner_Pagination {

    protected $_config = array(
        'current_page' => 1,
        'total_record' => 1,
        'total_page' => 1,
        'limit' => 10,
        'start' => 0,
        'link_full' => '',
        'link_first' => '',
    );

    function init($config = array()) {
        foreach ($config as $key => $val) {
            if (isset($this->_config[$key])) {
                $this->_config[$key] = $val;
            }
        }
        if ($this->_config['limit'] < 0) {
            $this->_config['limit'] = 0;
        }
        $this->_config['total_page'] = ceil($this->_config['total_record'] / $this->_config['limit']);
        if (!$this->_config['total_page']) {
            $this->_config['total_page'] = 1;
        }
        if ($this->_config['current_page'] < 1) {
            $this->_config['current_page'] = 1;
        }

        if ($this->_config['current_page'] > $this->_config['total_page']) {
            $this->_config['current_page'] = $this->_config['total_page'];
        }
        $this->_config['start'] = ($this->_config['current_page'] - 1) * $this->_config['limit'];
    }

    private function pglink($page) {
        if ($page <= 1 && $this->_config['link_first']) {
            return $this->_config['link_first'];
        }
        return str_replace('{p}', $page, $this->_config['link_full']);
    }

    function html() {
        $p = '';
        if ($this->_config['total_record'] > $this->_config['limit']) {
            $p = '<span class="pagination-links">';
            if ($this->_config['current_page'] > 1) {
                $p .= '<a class="prev-page" href="' . $this->pglink('1') . '"><span aria-hidden="true">&#8810;</span></a>';
                $p .= '<a class="prev-page" href="' . $this->pglink($this->_config['current_page'] - 1) . '">&lt;</a>';
            }
            $min = ($this->_config['current_page'] > 2) ? ($this->_config['current_page'] - 2) : 1;
            $max = ($this->_config['current_page'] < ($this->_config['total_page'] - 2)) ? ($this->_config['current_page'] + 2) : $this->_config['total_page'];
            for ($i = $min; $i <= $max; $i++) {
                if ($this->_config['current_page'] == $i) {
                    $p .= '<span class="tablenav-pages-navspan" aria-hidden="true">' . $i . '</span>';
                } else {
                    $p .= '<a class="prev-page" href="' . $this->pglink($i) . '">' . $i . '</a>';
                }
            }
            if ($this->_config['current_page'] < $this->_config['total_page']) {
                $p .= '<a class="next-page" href="' . $this->pglink($this->_config['current_page'] + 1) . '">&gt;</a>';
                $p .= '<a class="next-page" href="' . $this->pglink($this->_config['total_page']) . '">&#8811;</a>';    
            }
            $p .= '</span>';
            return $p;
        }
    }
}
    