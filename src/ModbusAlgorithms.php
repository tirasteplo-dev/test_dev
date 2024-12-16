<?php
namespace App;

use ModbusMaster;
use ModbusTcpClient\Network\BinaryStreamConnection;
use ModbusTcpClient\Packet\ModbusFunction\ReadHoldingRegistersRequest;
use ModbusTcpClient\Packet\ResponseFactory;
use PhpType;
class ModbusAlgorithms
{
    const MODBUS_SETTINGS = [
        "parity" => 9600,
        "host" => "192.168.2.3",
        "isRTU" => true,
    ];
    protected $conn;
    protected $heatbase;
    protected $modbus_addr;

    public function __construct($module)
    {
        $this->conn = new ModbusMaster($module->ip_addr, "UDP");
        $this->conn->client = self::MODBUS_SETTINGS['host'];
        $this->conn->port = $module->local_port;
        $this->conn->client_port = $module->dist_port;
        $this->conn->isRTU = true;
        $this->modbus_addr = $module->modbus_address;
    }

    public function getAnalogData($action)
    {
        // dd($action);s
        $analog = $this->conn->fc3($this->modbus_addr, $action->dec, (2));
        $analogParams = [];
        for ($idxParam = 0; $idxParam < 7; $idxParam++) {
            $numParam = $action->dec + $idxParam * 2;
            $analogParams[$numParam] = round(PhpType::bytes2float(
                array_slice($analog, ($idxParam * 4), 4)
            ), 2);

        }
        dd($analogParams);
    }


    public function getDiscreteData()
    {
        // dd($this->conn);
        // dd($this->heatbase);
    }
    public function setDiscreteData()
    {

    }
    public function setAnalogData()
    {

    }
}


/* ADIAS MODBUS EXAMPLE
public function __construct($module)
{
    $this->connection = BinaryStreamConnection::getBuilder()
        ->setHost($module->ip_addr)
        ->setPort($module->dist_port)
        ->setClient(self::MODBUS_SETTINGS['host'])
        ->setClientPort($module->local_port)
        ->build();
}

public function getAnalogData($action)
{
    $packet = new ReadHoldingRegistersRequest($action->dec, 1);
    try {
        $binaryData = $this->connection->connect()
            ->sendAndReceive($packet);
        echo 'Binary received (in hex):   ' . unpack('H*', $binaryData)[1] . PHP_EOL;


        //   @var $response ReadHoldingRegistersResponse
        $response = ResponseFactory::parseResponseOrThrow($binaryData);
        echo 'Parsed packet (in hex):     ' . $response->toHex() . PHP_EOL;
        echo 'Data parsed from packet (bytes):' . PHP_EOL;
        print_r($response->getData());

        foreach ($response as $word) {
            print_r($word->getBytes());
        }
        foreach ($response->asDoubleWords() as $doubleWord) {
            print_r($doubleWord->getBytes());
        }

        // set internal index to match start address to simplify array access
        $responseWithStartAddress = $response->withStartAddress($action->dec);
        print_r($responseWithStartAddress[$action->dec]->getBytes()); // use array access to get word
        print_r($responseWithStartAddress->getDoubleWordAt(257)->getFloat());

    } catch (Exception $exception) {
        echo 'An exception occurred' . PHP_EOL;
        echo $exception->getMessage() . PHP_EOL;
        echo $exception->getTraceAsString() . PHP_EOL;
    } finally {
        $this->connection->close();
    }
}
*/