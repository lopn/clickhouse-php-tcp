<?php


namespace CkTcp;


class Block
{
//Values     [][]interface{}
//Columns    []column.Column


//	offsets    []offset
//	buffers    []*buffer
//	info       blockInfo

    public $numRows = 0; //	NumRows    uint64
    public $numColumns = 0; //	NumColumns uint64


    /**
     * @var array
     * num1        uint64
     *isOverflows bool
     *num2        uint64
     *bucketNum   int32
     *num3        uint64
     */
    public $blockInfo = array(
        "num1"=>0,
        "isOverflows"=>false,
        "num2"=>0,
        "bucketNum"=>0,
        "num3"=>0

    );
    public function readData(Read &$reader,Types $types,&$rowData=array(),&$totalRows = 0,$serverInfo=array("name"=>"","major_version"=>0,"minor_version"=>0,"revision"=>0,"time_zone"=>'')){

        $reader->string();  // ----------
        if($serverInfo['revision'] > 0 ){
            $this->readBlockInfo($reader);
        }

        $this->numColumns = $reader->number();
        $this->numRows = $reader->number();

        for($i = 0; $i < $this->numColumns; $i++){
            $colKey  = $reader->string();
            $colType = $reader->string();

            if($this->numRows>0){
                $col = $types->unpack($colType, $this->numRows);
                foreach ($col as $index=>$colData) {
                $rowData[$index + $totalRows][$colKey] = $colData;
                }
            }
            unset($col);
        }
        $totalRows += $this->numRows;



	}


	public function readBlockInfo(Read &$reader){
        $this->blockInfo['num1'] = $reader->number();
        $this->blockInfo['is_overflows'] = $reader->number();
        $this->blockInfo['num2'] = $reader->number();
        $this->blockInfo['bucket_num'] = $reader->int();
        $this->blockInfo['num3'] = $reader->number();
    }

}