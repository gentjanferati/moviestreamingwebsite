<?php 
function slugify($text)
{
  $text = preg_replace('~[^\pL\d]+~u', '-', $text);     // replace non letter or digits by -
  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);  // transliterate
  $text = preg_replace('~[^-\w]+~', '', $text);         // remove unwanted characters
  $text = trim($text, '-');                             // trim
  $text = preg_replace('~-+~', '-', $text);             // remove duplicate -
  $text = strtolower($text);                            // lowercase
  if (empty($text)) {
    return 'n-a';
  }
  return $text;
}

function replaceAccents($str) {
  $replace = array(
      'ъ'=>'-', 'Ь'=>'-', 'Ъ'=>'-', 'ь'=>'-',
      'Ă'=>'A', 'Ą'=>'A', 'À'=>'A', 'Ã'=>'A', 'Á'=>'A', 'Æ'=>'A', 'Â'=>'A', 'Å'=>'A', 'Ä'=>'Ae',
      'Þ'=>'B',
      'Ć'=>'C', 'ץ'=>'C', 'Ç'=>'C',
      'È'=>'E', 'Ę'=>'E', 'É'=>'E', 'Ë'=>'E', 'Ê'=>'E',
      'Ğ'=>'G',
      'İ'=>'I', 'Ï'=>'I', 'Î'=>'I', 'Í'=>'I', 'Ì'=>'I',
      'Ł'=>'L',
      'Ñ'=>'N', 'Ń'=>'N',
      'Ø'=>'O', 'Ó'=>'O', 'Ò'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'Oe',
      'Ş'=>'S', 'Ś'=>'S', 'Ș'=>'S', 'Š'=>'S',
      'Ț'=>'T',
      'Ù'=>'U', 'Û'=>'U', 'Ú'=>'U', 'Ü'=>'Ue',
      'Ý'=>'Y',
      'Ź'=>'Z', 'Ž'=>'Z', 'Ż'=>'Z',
      'â'=>'a', 'ǎ'=>'a', 'ą'=>'a', 'á'=>'a', 'ă'=>'a', 'ã'=>'a', 'Ǎ'=>'a', 'а'=>'a', 'А'=>'a', 'å'=>'a', 'à'=>'a', 'א'=>'a', 'Ǻ'=>'a', 'Ā'=>'a', 'ǻ'=>'a', 'ā'=>'a', 'ä'=>'ae', 'æ'=>'ae', 'Ǽ'=>'ae', 'ǽ'=>'ae',
      'б'=>'b', 'ב'=>'b', 'Б'=>'b', 'þ'=>'b',
      'ĉ'=>'c', 'Ĉ'=>'c', 'Ċ'=>'c', 'ć'=>'c', 'ç'=>'c', 'ц'=>'c', 'צ'=>'c', 'ċ'=>'c', 'Ц'=>'c', 'Č'=>'c', 'č'=>'c', 'Ч'=>'ch', 'ч'=>'ch',
      'ד'=>'d', 'ď'=>'d', 'Đ'=>'d', 'Ď'=>'d', 'đ'=>'d', 'д'=>'d', 'Д'=>'D', 'ð'=>'d',
      'є'=>'e', 'ע'=>'e', 'е'=>'e', 'Е'=>'e', 'Ə'=>'e', 'ę'=>'e', 'ĕ'=>'e', 'ē'=>'e', 'Ē'=>'e', 'Ė'=>'e', 'ė'=>'e', 'ě'=>'e', 'Ě'=>'e', 'Є'=>'e', 'Ĕ'=>'e', 'ê'=>'e', 'ə'=>'e', 'è'=>'e', 'ë'=>'e', 'é'=>'e',
      'ф'=>'f', 'ƒ'=>'f', 'Ф'=>'f',
      'ġ'=>'g', 'Ģ'=>'g', 'Ġ'=>'g', 'Ĝ'=>'g', 'Г'=>'g', 'г'=>'g', 'ĝ'=>'g', 'ğ'=>'g', 'ג'=>'g', 'Ґ'=>'g', 'ґ'=>'g', 'ģ'=>'g',
      'ח'=>'h', 'ħ'=>'h', 'Х'=>'h', 'Ħ'=>'h', 'Ĥ'=>'h', 'ĥ'=>'h', 'х'=>'h', 'ה'=>'h',
      'î'=>'i', 'ï'=>'i', 'í'=>'i', 'ì'=>'i', 'į'=>'i', 'ĭ'=>'i', 'ı'=>'i', 'Ĭ'=>'i', 'И'=>'i', 'ĩ'=>'i', 'ǐ'=>'i', 'Ĩ'=>'i', 'Ǐ'=>'i', 'и'=>'i', 'Į'=>'i', 'י'=>'i', 'Ї'=>'i', 'Ī'=>'i', 'І'=>'i', 'ї'=>'i', 'і'=>'i', 'ī'=>'i', 'ĳ'=>'ij', 'Ĳ'=>'ij',
      'й'=>'j', 'Й'=>'j', 'Ĵ'=>'j', 'ĵ'=>'j', 'я'=>'ja', 'Я'=>'ja', 'Э'=>'je', 'э'=>'je', 'ё'=>'jo', 'Ё'=>'jo', 'ю'=>'ju', 'Ю'=>'ju',
      'ĸ'=>'k', 'כ'=>'k', 'Ķ'=>'k', 'К'=>'k', 'к'=>'k', 'ķ'=>'k', 'ך'=>'k',
      'Ŀ'=>'l', 'ŀ'=>'l', 'Л'=>'l', 'ł'=>'l', 'ļ'=>'l', 'ĺ'=>'l', 'Ĺ'=>'l', 'Ļ'=>'l', 'л'=>'l', 'Ľ'=>'l', 'ľ'=>'l', 'ל'=>'l',
      'מ'=>'m', 'М'=>'m', 'ם'=>'m', 'м'=>'m',
      'ñ'=>'n', 'н'=>'n', 'Ņ'=>'n', 'ן'=>'n', 'ŋ'=>'n', 'נ'=>'n', 'Н'=>'n', 'ń'=>'n', 'Ŋ'=>'n', 'ņ'=>'n', 'ŉ'=>'n', 'Ň'=>'n', 'ň'=>'n',
      'о'=>'o', 'О'=>'o', 'ő'=>'o', 'õ'=>'o', 'ô'=>'o', 'Ő'=>'o', 'ŏ'=>'o', 'Ŏ'=>'o', 'Ō'=>'o', 'ō'=>'o', 'ø'=>'o', 'ǿ'=>'o', 'ǒ'=>'o', 'ò'=>'o', 'Ǿ'=>'o', 'Ǒ'=>'o', 'ơ'=>'o', 'ó'=>'o', 'Ơ'=>'o', 'œ'=>'oe', 'Œ'=>'oe', 'ö'=>'oe',
      'פ'=>'p', 'ף'=>'p', 'п'=>'p', 'П'=>'p',
      'ק'=>'q',
      'ŕ'=>'r', 'ř'=>'r', 'Ř'=>'r', 'ŗ'=>'r', 'Ŗ'=>'r', 'ר'=>'r', 'Ŕ'=>'r', 'Р'=>'r', 'р'=>'r',
      'ș'=>'s', 'с'=>'s', 'Ŝ'=>'s', 'š'=>'s', 'ś'=>'s', 'ס'=>'s', 'ş'=>'s', 'С'=>'s', 'ŝ'=>'s', 'Щ'=>'sch', 'щ'=>'sch', 'ш'=>'sh', 'Ш'=>'sh', 'ß'=>'ss',
      'т'=>'t', 'ט'=>'t', 'ŧ'=>'t', 'ת'=>'t', 'ť'=>'t', 'ţ'=>'t', 'Ţ'=>'t', 'Т'=>'t', 'ț'=>'t', 'Ŧ'=>'t', 'Ť'=>'t', '™'=>'tm',
      'ū'=>'u', 'у'=>'u', 'Ũ'=>'u', 'ũ'=>'u', 'Ư'=>'u', 'ư'=>'u', 'Ū'=>'u', 'Ǔ'=>'u', 'ų'=>'u', 'Ų'=>'u', 'ŭ'=>'u', 'Ŭ'=>'u', 'Ů'=>'u', 'ů'=>'u', 'ű'=>'u', 'Ű'=>'u', 'Ǖ'=>'u', 'ǔ'=>'u', 'Ǜ'=>'u', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'У'=>'u', 'ǚ'=>'u', 'ǜ'=>'u', 'Ǚ'=>'u', 'Ǘ'=>'u', 'ǖ'=>'u', 'ǘ'=>'u', 'ü'=>'ue',
      'в'=>'v', 'ו'=>'v', 'В'=>'v',
      'ש'=>'w', 'ŵ'=>'w', 'Ŵ'=>'w',
      'ы'=>'y', 'ŷ'=>'y', 'ý'=>'y', 'ÿ'=>'y', 'Ÿ'=>'y', 'Ŷ'=>'y',
      'Ы'=>'y', 'ž'=>'z', 'З'=>'z', 'з'=>'z', 'ź'=>'z', 'ז'=>'z', 'ż'=>'z', 'ſ'=>'z', 'Ж'=>'zh', 'ж'=>'zh'
  );
  $str = strtr($str, $replace);
  $str = str_replace("’","'",$str);
  $str = str_replace("'","''",$str);
  return $str;
}

function pagination($conn, $query, $per_page = 10,$page = 1, $url = '?'){        
  $query = "SELECT COUNT(*) as num FROM {$query}";
  $res = $conn->query($query);
  $row = $res->fetch_array();
  $total = $row['num'];
  $adjacents = "2"; 

  $page = ($page == 0 ? 1 : $page);  
  $start = ($page - 1) * $per_page;								

  $prev = $page - 1;							
  $next = $page + 1;
    $lastpage = ceil($total/$per_page);
  $lpm1 = $lastpage - 1;
  
  $pagination = "";
  if($lastpage > 1)
  {	
    $pagination .= "<ul class='pagination'>";
                $pagination .= "<li class='details' style='margin-top:2px'>Page $page of $lastpage</li>";
    if ($lastpage < 7 + ($adjacents * 2))
    {	
      for ($counter = 1; $counter <= $lastpage; $counter++)
      {
        if ($counter == $page)
          $pagination.= "<li><a class='current'>$counter</a></li>";
        else
          $pagination.= "<li><a href='{$url}$counter/'>$counter</a></li>";					
      }
    }
    elseif($lastpage > 5 + ($adjacents * 2))
    {
      if($page < 1 + ($adjacents * 2))		
      {
        for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
        {
          if ($counter == $page)
            $pagination.= "<li><a class='current'>$counter</a></li>";
          else
            $pagination.= "<li><a href='{$url}$counter/'>$counter</a></li>";					
        }
        $pagination.= "<li class='dot'>...</li>";
        $pagination.= "<li><a href='{$url}$lpm1/'>$lpm1</a></li>";
        $pagination.= "<li><a href='{$url}$lastpage/'>$lastpage</a></li>";		
      }
      elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
      {
        $pagination.= "<li><a href='{$url}1/'>1</a></li>";
        $pagination.= "<li><a href='{$url}2/'>2</a></li>";
        $pagination.= "<li class='dot'>...</li>";
        for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
        {
          if ($counter == $page)
            $pagination.= "<li><a class='current'>$counter</a></li>";
          else
            $pagination.= "<li><a href='{$url}$counter/'>$counter</a></li>";					
        }
        $pagination.= "<li class='dot'>..</li>";
        $pagination.= "<li><a href='{$url}$lpm1/'>$lpm1</a></li>";
        $pagination.= "<li><a href='{$url}$lastpage/'>$lastpage</a></li>";		
      }
      else
      {
        $pagination.= "<li><a href='{$url}1/'>1</a></li>";
        $pagination.= "<li><a href='{$url}2/'>2</a></li>";
        $pagination.= "<li class='dot'>..</li>";
        for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
        {
          if ($counter == $page)
            $pagination.= "<li><a class='current'>$counter</a></li>";
          else
            $pagination.= "<li><a href='{$url}$counter/'>$counter</a></li>";					
        }
      }
    }
    
    if ($page < $counter - 1){ 
      $pagination.= "<li><a href='{$url}$next/'>Next</a></li>";
            $pagination.= "<li><a href='{$url}$lastpage/'>Last</a></li>";
    } else {
            $pagination.= "<li><a class='current'>Next</a></li>";
            $pagination.= "<li><a class='current'>Last</a></li>";
        }
    $pagination.= "</ul>\n";		
  }
    return $pagination;
}

/* Status Numbers
0 = Not Logged In
1 = No Subscription
2 = Valid Subscription*/
function userStatus($conn) {
  if(!isset($_SESSION)) { session_start(); }
  if(isset($_SESSION['_user_id'])) {
    $id = $_SESSION['_user_id'];
    $sql = "SELECT * FROM user WHERE id='$id'";
    $res = $conn->query($sql);

    $row = $res->fetch_array(MYSQLI_ASSOC);
    $status = $row['status'];

    if($status == 'Subscribed') {
      return '2';
    } else {
      return '1';
    }

  } else {
    return '0';
  }
}

?>