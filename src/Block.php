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

        $test = $reader->string();
        if($serverInfo['revision'] > 0 ){
            $this->readBlockInfo($reader);
        }

        $this->numColumns = $reader->number();
        $this->numRows = $reader->number();


//        var_dump($this->blockInfo);

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
///
//	for i := 0; i < int(block.NumColumns); i++ {
//        var (
//        value      interface{}
//			columnName string
//			columnType string
//		)
//		if columnName, err = decoder.String(); err != nil {
//            return err
//		}
//		if columnType, err = decoder.String(); err != nil {
//            return err
//		}
//		c, err := column.Factory(columnName, columnType, serverInfo.Timezone)
//		if err != nil {
//            return err
//		}
//		block.Columns = append(block.Columns, c)
//		switch column := c.(type) {
//            case *column.Array:
//			if block.Values[i], err = column.ReadArray(decoder, int(block.NumRows)); err != nil {
//                    return err
//			}
//            case *column.Nullable:
//			if block.Values[i], err = column.ReadNull(decoder, int(block.NumRows)); err != nil {
//                    return err
//			}
//            case *column.Tuple:
//			if block.Values[i], err = column.ReadTuple(decoder, int(block.NumRows)); err != nil {
//                    return err
//			}
//            default:
//                for row := 0; row < int(block.NumRows); row++ {
//                    if value, err = column.Read(decoder, false); err != nil {
//                        return err
//				}
//				fmt.Print(value)
//				block.Values[i] = append(block.Values[i], value)
//			}
//		}
//	}
//	fmt.Println("block data")
//	fmt.Println(block.Values)
//	return nil






}