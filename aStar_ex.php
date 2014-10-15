<?php

// A* algorithm by aaz, found at
// http://althenia.net/svn/stackoverflow/a-star.php?rev=7
// Binary min-heap with element values stored separately

function heap_float(&$heap, &$values, $i, $index) {
    for (; $i; $i = $j) {
        $j = ($i + $i%2)/2 - 1;
        if ($values[$heap[$j]] < $values[$index])
            break;
        $heap[$i] = $heap[$j];
    }
    $heap[$i] = $index;
}

function heap_push(&$heap, &$values, $index) {
    heap_float($heap, $values, count($heap), $index);
}

function heap_raise(&$heap, &$values, $index) {
    heap_float($heap, $values, array_search($index, $heap), $index);
}

function heap_pop(&$heap, &$values) {
    $front = $heap[0];
    $index = array_pop($heap);
    $n = count($heap);
    if ($n) {
        for ($i = 0;; $i = $j) {
            $j = $i*2 + 1;
            if ($j >= $n)
                break;
            if ($j+1 < $n && $values[$heap[$j+1]] < $values[$heap[$j]])
                ++$j;
            if ($values[$index] < $values[$heap[$j]])
                break;
            $heap[$i] = $heap[$j];
        }
        $heap[$i] = $index;
    }
    return $front;
}


// A-star algorithm:
//   $start, $target - node indexes
//   $neighbors($i)     - map of neighbor index => step cost
//   $heuristic($i, $j) - minimum cost between $i and $j

function a_star($start, $target, $neighbors, $heuristic) {
    $open_heap = array($start); // binary min-heap of indexes with values in $f
    $open      = array($start => TRUE); // set of indexes
    $closed    = array();               // set of indexes

    $g[$start] = 0;
    $h[$start] = $heuristic($start, $target);
    $f[$start] = $g[$start] + $h[$start];

    while ($open) {
        $i = heap_pop($open_heap, $f);
        unset($open[$i]);
        $closed[$i] = TRUE;

        if ($i == $target) {
            $path = array();
            for (; $i != $start; $i = $from[$i])
                $path[] = $i;
            return array_reverse($path);
        }

        foreach ($neighbors($i) as $j => $step)
            if (!array_key_exists($j, $closed))
                if (!array_key_exists($j, $open) || $g[$i] + $step < $g[$j]) {
                    $g[$j] = $g[$i] + $step;
                    $h[$j] = $heuristic($j, $target);
                    $f[$j] = $g[$j] + $h[$j];
                    $from[$j] = $i;

                    if (!array_key_exists($j, $open)) {
                        $open[$j] = TRUE;
                        heap_push($open_heap, $f, $j);
                    } else
                        heap_raise($open_heap, $f, $j);
                }
    }

    return FALSE;
}


// Example with maze

$width  = 51;
$height = 23;

$map = array_fill(0, $height, str_repeat('A', $width));

function node($x, $y) {
    global $width;
    return $y * $width + $x;
}

function coord($i) {
    global $width;
    $x = $i % $width;
    $y = ($i - $x) / $width;
    return array($x, $y);
}

function neighbors($i) {
    global $map, $width, $height;
    list ($x, $y) = coord($i);
    $neighbors = array();
    if ($x-1 >= 0      && $map[$y][$x-1] == ' ') $neighbors[node($x-1, $y)] = 1;
    if ($x+1 < $width  && $map[$y][$x+1] == ' ') $neighbors[node($x+1, $y)] = 1;
    if ($y-1 >= 0      && $map[$y-1][$x] == ' ') $neighbors[node($x, $y-1)] = 1;
    if ($y+1 < $height && $map[$y+1][$x] == ' ') $neighbors[node($x, $y+1)] = 1;
    return $neighbors;
}

function heuristic($i, $j) {
    list ($i_x, $i_y) = coord($i);
    list ($j_x, $j_y) = coord($j);
    return abs($i_x - $j_x) + abs($i_y - $j_y);
}

function generate($i) {
    global $map, $width, $height;
    list ($x, $y) = coord($i);
    $map[$y][$x] = ' ';
    $next = array();
    if ($x-2 > 0)         $next[] = array(-2, 0);
    if ($x+2 < $width-1)  $next[] = array(+2, 0);
    if ($y-2 > 0)         $next[] = array(0, -2);
    if ($y+2 < $height-1) $next[] = array(0, +2);
    shuffle($next);
    foreach ($next as $d)
        if ($map[$y+$d[1]  ][$x+$d[0]  ] != ' ') {
            $map[$y+$d[1]/2][$x+$d[0]/2]  = ' ';
            generate(node($x+$d[0], $y+$d[1]));
        }
}

generate(node(rand(1, ($width +$width %2)/2-1)*2-1,
              rand(1, ($height+$height%2)/2-1)*2-1));

$start  = node(1, 1);
$target = node($width+$width%2-3, $height+$height%2-3);

$path = a_star($start, $target, 'neighbors', 'heuristic');

array_unshift($path, $start);
foreach ($path as $i) {
    list ($x, $y) = coord($i);
    $map[$y][$x] = '*';
}

function display_maze($map) {
    foreach ($map as $line) {
      echo str_replace("A","<span></span>",str_replace("*","<img src='google_smile.gif' width='12' height='12'>",$line))."
";
    }
}
?>

<html>
<head>
  <style type="text/css">
  /* Maze container */
  div {
    font-family:courier new;
    white-space: pre;  
    font-size:20px;
    line-height:10px;
  }
  /* Maze wall */
  span {    
    display:inline-block;
    width:12px;
    height:12px;
    background:#ccc;
  }
  a, a:visited {
    background: #00688b;
    text-align:center;
    color:#fff;
    font-weight:bold;
    width:<?php echo 12*$width; ?>px;
    display:block;
    font-family:courier new;
    text-decoration:none;
    font-size:20px;
    line-height:40px;
    -webkit-border-bottom-right-radius: 7px;
    -webkit-border-bottom-left-radius: 7px;
    -moz-border-radius-bottomright: 7px;
    -moz-border-radius-bottomleft: 7px;
    border-bottom-right-radius: 7px;
    border-bottom-left-radius: 7px;
  }
  a:hover {
    background: #0099CC;
  }
  </style>
</head>
<body>
  <div><?php display_maze($map); ?></div>
  <a href="javascript:location.reload(true)">Refresh</a>
</body>
</html>