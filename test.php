<?php
require __DIR__ . '/vendor/autoload.php';

use CkTcp\Client;
use CkTcp\Types;


$t1 = microtime(true);
$ck = new Client('tcp://127.0.0.1:9000');
//$ck = new \OneCk\Client('tcp://192.168.23.129:9091', 'default', '123456', 'test1');


$data['server info'] = $ck->getServerInfo();
$data['drop table']  = $ck->query('DROP TABLE IF EXISTS t6');

$res = $ck->query("SELECT sipHash64(toString('1ace54')) AS result");
if($res[0]['result'] !== '9525649478782099197'){
    throw new \Exception('test uint64 fail:' . __LINE__);
}
$table                = [
    'CREATE TABLE t6 (',
    '`id` UInt32,',
    '`f1` Int32,',
    '`f2` Nullable(Int32),',
    '`f3` UInt8,',
    '`f4` Nullable(UInt8),',
    '`f5` UInt16,',
    '`f6` UInt64,',
    '`f7` Int64,',
    '`f8` Float32,',
    '`f9` Float64,',
    '`f10` Nullable(Float64),',
    '`f11` Decimal32(3),',
    '`f12` Decimal64(5),',
    '`f13` Decimal128(7),',
    '`f14` Nullable(Decimal128(7)),',
    '`f15` String,',
    '`f16` Nullable(String),',
    '`f17` FixedString(32),',
    '`f18` UUID,',
    '`f19` Date,',
    '`f20` Nullable(Date),',
    '`f21` Datetime,',
    '`f22` Datetime64(3),',
    '`f23` IPv4,',
    '`f24` Nullable(IPv4),',
    '`f25` IPv6,',
    '`f26` LowCardinality(String),',
    '`f27` Array(Int32),',
    '`f28` Array(Array(Array(Nullable(Date)))),',
    '`f29` Array(Array(Array(Array(Array(Nullable(Datetime))))))',
    ') ENGINE = MergeTree() ORDER BY id SETTINGS index_granularity = 8192'
];
$data['create table'] = $ck->query(implode("\n", $table));

$data['insert data'] = $ck->insert('t6', [
    [
        'id'  => 1,
        'f1'  => -3,
        'f2'  => null,
        'f3'  => 127,
        'f4'  => null,
        'f5'  => 3322,
        'f6'  => 1844674407370955161,
        'f7'  => 9223372036854775807,
        'f8'  => -2132121.5,
        'f9'  => 6546546544665.66658,
        'f10' => null,
        'f11' => 552.339,
        'f12' => 3658.6954,
        'f13' => '170141183460469231168730371588.4105721',
        'f14' => null,
        'f15' => 'emoji😀😁😂😃😄',
        'f16' => null,
        'f17' => md5('a'),
        'f18' => '016e64be-605f-4108-8a67-495d74d7ef3c',
        'f19' => '2020-09-05',
        'f20' => null,
        'f21' => '2020-09-05 14:25:12',
        'f22' => '2020-09-05 14:25:12.258',
        'f23' => '192.168.1.1',
        'f24' => null,
        'f25' => 'CDCD:910A:2222:5498:8475:1111:3900:2020',
        'f26' => 'eee',
        'f27' => [0, -2, 3, 4, 5, 6, 7, 8, 64],
        'f28' => [[['2020-01-05', null, '2020-01-06']], [['2020-01-07'], ['2020-01-08']], [['2020-01-09']]],
        'f29' => [[[[["2020-01-05 05:05:05", null, "2020-01-06 15:16:17"]], [["2020-01-07 18:19:20"], ["2020-01-08 21:22:23"]], [["2020-01-09 00:00:00"]]], [[["2020-01-10 01:00:00", null]]]], [[[["2020-01-11 00:00:01", null, "2020-01-12 11:01:58"]], [["2020-01-13 21:22:01"]]]]]
    ],
    [
        'id'  => 2,
        'f1'  => 3,
        'f2'  => 3,
        'f3'  => 3,
        'f4'  => 3,
        'f5'  => 3,
        'f6'  => '9844674407370955161',// uint64  > PHP_INT_MAX
        'f7'  => 3,
        'f8'  => 3,
        'f9'  => 3,
        'f10' => 3,
        'f11' => -552.339,
        'f12' => -3658.6954,
        'f13' => '-170141183460469231168730371588.4105721',
        'f14' => 3,
        'f15' => str_repeat(md5('aa'), '6'),
        'f16' => '',
        'f17' => md5('55'),
        'f18' => md5('55'),
        'f19' => '2020-09-06',
        'f20' => '2020-09-06',
        'f21' => '2020-09-06 14:25:12',
        'f22' => '2020-09-06 14:25:12.258',
        'f23' => '251.222.221.231',
        'f24' => '192.168.1.2',
        'f25' => '1030::C9B4:FF12:48AA:1A2B',
        'f26' => 'eee22',
        'f27' => [1, 2, 3, 4],
        'f28' => [[['2020-01-05', '2020-01-06']], [['2020-01-07', null], ['2020-01-08']], [['2020-01-09']]],
        'f29' => [[[[[null]]]]]
    ],
    [
        'id'  => 3,
        'f1'  => -1,
        'f2'  => 3,
        'f3'  => 3,
        'f4'  => 3,
        'f5'  => 3,
        'f6'  => 3,
        'f7'  => 3,
        'f8'  => 3,
        'f9'  => 3,
        'f10' => 3,
        'f11' => 3,
        'f12' => 3,
        'f13' => 3,
        'f14' => 3,
        'f15' => 'aaa',
        'f16' => 'aaa',
        'f17' => md5('a'),
        'f18' => '3026ee79-ac2a-46d2-882d-959a55d71025',
        'f19' => '2020-09-07',
        'f20' => '2020-09-07',
        'f21' => '2020-09-07 14:25:12',
        'f22' => '2020-09-07 14:25:12.258',
        'f23' => '192.168.1.1',
        'f24' => null,
        'f25' => '2001:DB8:2de::e13',
        'f26' => 'eee22',
        'f27' => [12344],
        'f28' => [[['2020-01-05', '2020-01-06'], [null]], [['2020-01-07'], ['2020-01-08']], [['2020-01-09']]],
        'f29' => [[[[['2018-01-25 11:25:14']]]]]
    ]
]);


//$data['struct'] = $ck->query('desc t6');


$data['select t6'] = $ck->query('select * from t6');

$data['select t6 int64'] = $ck->query("select id,f6 from t6 where f6=1844674407370955161");

$data['select t6 Decimal32'] = $ck->query("select id,f11 from t6 where f11='552.339'");

$data['select t6 Decimal64'] = $ck->query("select id,f12 from t6 where f12='-3658.69540'");

$data['select t6 Decimal128'] = $ck->query("select id,f13 from t6 where f13='170141183460469231168730371588.4105721'");

$data['select t6 uuid'] = $ck->query("select id,f18 from t6 where f18='3026ee79-ac2a-46d2-882d-959a55d71025'");

$data['select t6 date'] = $ck->query("select id,f19 from t6 where f19='2020-09-05'");

$data['select t6 datetime'] = $ck->query("select id,f21 from t6 where f21='2020-09-07 20:25:12'");

$data['select t6 datetime64'] = $ck->query("select id,f22 from t6 where f22='2020-09-06 20:25:12.258'");

$data['select t6 ip'] = $ck->query("select id,f23,f25 from t6 where f23=" . Types::encodeIpv4('192.168.1.1'));

$data['select t6 ip64'] = $ck->query("select id,f23,f25 from t6 where f25='" . Types::encodeIpv6('1030::c9b4:ff12:48aa:1a2b') . "'");

$data['nothing'] = $ck->query('select array()');

$data['drop table']   = $ck->query('DROP TABLE IF EXISTS t5');
$table                = [
    'CREATE TABLE t5 (',
    '`id` UInt32,',
    '`f1` UInt16,',
    '`f2` SimpleAggregateFunction(max, DateTime64)',
    ') ENGINE = AggregatingMergeTree() ORDER BY (id, f1) SETTINGS index_granularity = 8192'
];
$data['create table'] = $ck->query(implode("\n", $table));

$data['insert data'] = $ck->insert('t5', [
    [
        'id' => 1,
        'f1' => 1,
        'f2' => '2020-09-29 14:25:12.258'
    ],
    [
        'id' => 1,
        'f1' => 1,
        'f2' => '2020-09-29 14:30:56.873'
    ],
    [
        'id' => 1,
        'f1' => 1,
        'f2' => '2020-09-29 14:35:46.456'
    ],
    [
        'id' => 1,
        'f1' => 1,
        'f2' => '2020-09-29 14:40:16.387'
    ],
    [
        'id' => 1,
        'f1' => 1,
        'f2' => '2020-09-29 14:45:22.111'
    ]
]);

$data['select t5 saf'] = $ck->query("select id,f1,max(f2) from t5 group by id, f1");


$data['drop table'] = $ck->query('DROP TABLE IF EXISTS tt1');
$ck->query("CREATE TABLE IF NOT EXISTS tt1 (
  some_uuid UUID,
  entity_id UInt32,
  parameter_id UInt64,
  creation_ts SimpleAggregateFunction(max, DateTime64) DEFAULT NOW(),
  number SimpleAggregateFunction(sum, UInt64) DEFAULT 1
) engine = AggregatingMergeTree()
PARTITION BY toYYYYMM(creation_ts)
ORDER BY (some_uuid, entity_id, parameter_id)");

$ck->insert('tt1', [
    [
        'some_uuid'    => 'b957bda7-c368-4f43-bc06-640fc6edc466',
        'entity_id'    => 4,
        'parameter_id' => 4,
        'number'       => 1,
    ],
    [
        'some_uuid'    => 'b957bda7-c368-4f43-bc06-640fc6edc466',
        'entity_id'    => 4,
        'parameter_id' => 7,
        'number'       => 1,
    ],
    [
        'some_uuid'    => 'b957bda7-c368-4f43-bc06-640fc6edc466',
        'entity_id'    => 5,
        'parameter_id' => 5,
        'number'       => 1,
    ],
    [
        'some_uuid'    => 'b957bda7-c368-4f43-bc06-640fc6edc466',
        'entity_id'    => 6,
        'parameter_id' => 6,
        'number'       => 1,
    ],
    [
        'some_uuid'    => 'b957bda7-c368-4f43-bc06-640fc6edc466',
        'entity_id'    => 5,
        'parameter_id' => 5,
        'number'       => 1,
    ]
]);

$tmp_data = $ck->query("SELECT
    entity_id,
    parameter_id,
    sumOrNullIf(number, some_uuid NOT IN ('b957bda7-c368-4f43-bc06-640fc6edc466')) AS number_0,
    maxOrNullIf(creation_ts, some_uuid NOT IN ('b957bda7-c368-4f43-bc06-640fc6edc466')) as creation_ts_0,
    sumOrNullIf(number, some_uuid NOT IN ('c34b4e9f-690e-4f24-b67c-206242a4287c')) AS number_1,
    maxOrNullIf(creation_ts, some_uuid NOT IN ('c34b4e9f-690e-4f24-b67c-206242a4287c')) as creation_ts_1
FROM tt1
WHERE some_uuid IN ('b957bda7-c368-4f43-bc06-640fc6edc466','c34b4e9f-690e-4f24-b67c-206242a4287c')
GROUP BY entity_id, parameter_id
ORDER BY entity_id");
foreach ($tmp_data as $v) {
    if ($v['number_1'] == null || $v['creation_ts_1'] == null) {
        throw new \Exception('test fail line:' . __LINE__);
    }
}

// flow of  write
$data['drop table']   = $ck->query('DROP TABLE IF EXISTS t7');
$table                = [
    'CREATE TABLE t7 (',
    '`id` UInt32,',
    '`f2` Nullable(Int32),',
    '`f5` UInt16,',
    '`f15` String',
    ') ENGINE = MergeTree() ORDER BY id SETTINGS index_granularity = 8192'
];
$data['create table'] = $ck->query(implode("\n", $table));
$ck->writeStart('t7', ['id', 'f2', 'f5', 'f15']);
for ($i = 0; $i < 1000; $i++) {
    $da = [];
    for ($j = 0; $j < 1000; $j++) {
        $da[] = [
            'id'  => mt_rand(1, 1000000),
            'f2'  => mt_rand(-1000000, 1000000),
            'f5'  => mt_rand(1, 10000),
            'f15' => md5(mt_rand(1, 10000))
        ];
    }
    $ck->writeBlock($da);
}

$ck->writeEnd();

$data['write 100w rows time'] = microtime(true) - $t1;

echo json_encode($data);
