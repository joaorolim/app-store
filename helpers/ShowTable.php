<?php

class ShowTable
{
	protected $columns = array();
	protected $rows = NULL;

    public function __construct($col=array(), $rows=NULL)
    {
        $this->columns = $col;
        $this->rows = $rows;
    }


    public function create()
    {
        $table = '<table>';

		// head of the table
		$table .=  '<tr>';
		foreach ($this->columns as $column)
		{
			$table .= '<th>'.$column.'</th>';
		}
		$table .= '<th> </th>';
		$table .= '<th> </th>';
		$table .=  '</tr>';


		// body of the table
		foreach ($this->rows as $obj)
		{
			$table .=  '<tr>';

			foreach ($obj as $key => $value)
			{
				if ( is_numeric($value) && strpos($value, ".") !== false )
				{
					$table .= '<td>'.number_format((float)$value, 2, '.', '').'</td>';
				}
				else
				{
					$table .= '<td>'.$value.'</td>';
				}
			}
			$table .= '<td><a class="exc_tbl" href="/processaexcluir.php?codigo='.$obj->id.'">Excluir</td>';
			$table .= '<td><a class="alt_tbl" href="/alterar.php?codigo='.$obj->id.'">Alterar</td>';
			$table .=  '</tr>';
		}

		$table .=  '</table>';

		return $table;

    }

}

