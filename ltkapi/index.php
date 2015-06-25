<?php
/**
 * index.php
 */
// Configuration
if (is_file('config.php')) {
	require_once('config.php');
}
ini_set("soap.wsdl_cache_enabled", "0"); // отключаем кеширование WSDL-файла для тестирования
header("Content-Type: text/xml; charset=utf-8");
echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>";
?>
<definitions xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/"
             xmlns:soapenc="http://schemas.xmlsoap.org/soap/encoding/"
             xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
             xmlns:mime="http://schemas.xmlsoap.org/wsdl/mime/"
             xmlns:tns="<?php echo HTTP_LTK; ?>"
             xmlns:xs="http://www.w3.org/2001/XMLSchema"
             xmlns:soap12="http://schemas.xmlsoap.org/wsdl/soap12/"
             xmlns:http="http://schemas.xmlsoap.org/wsdl/http/"
             name="LTKWsdl"
             xmlns="http://schemas.xmlsoap.org/wsdl/">
    
    
    
    
    <!-- Описание типов данных -->
    <types>
        <xs:schema xmlns:tns="http://schemas.xmlsoap.org/wsdl/"
                   xmlns="http://www.w3.org/2001/XMLSchema"
                   xmlns:xs="http://www.w3.org/2001/XMLSchema"
                   elementFormDefault="qualified"
                   targetNamespace="<?php echo HTTP_LTK; ?>">
            
            <complexType name="Customer">
                <sequence>
                    <element name="customer_id"         type="xs:int" minOccurs="1" maxOccurs="1" />
                    <element name="external_id"         type="xs:string" minOccurs="1" maxOccurs="1" />
                    <element name="date_added"          type="xs:dateTime" minOccurs="1" maxOccurs="1" />
                    <element name="status"              type="xs:boolean" minOccurs="1" maxOccurs="1" />
                    <element name="customer_group_id"   type="xs:int" minOccurs="1" maxOccurs="1" />
                    <element name="customer_group"      type="xs:string" minOccurs="1" maxOccurs="1" />
                    <element name="firstname"           type="xs:string" minOccurs="1" maxOccurs="1" />
                    <element name="lastname"            type="xs:string" minOccurs="1" maxOccurs="1" />
                    <element name="email"               type="xs:string" minOccurs="1" maxOccurs="1" />
                    <element name="telephone"           type="xs:string" minOccurs="1" maxOccurs="1" />
                    <element name="password"            type="xs:string" minOccurs="1" maxOccurs="1" />
                    <element name="newsletter"          type="xs:boolean" minOccurs="1" maxOccurs="1" />
                </sequence>
            </complexType>
            
            <complexType name="Customers">
                <sequence>
                    <element minOccurs="0" maxOccurs="unbounded" name="Customer" type="tns:Customer"/>
                </sequence>
            </complexType>
            
            <complexType name="CustomerResponseData">
                <sequence>
                    <element minOccurs="0" maxOccurs="1" name="Customer" type="tns:Customer"/>
                    <element minOccurs="1" maxOccurs="1" name="Status" type="xs:boolean"/>
                </sequence>
            </complexType>
            
            <element name="CustomerRequest">
                <complexType>
                    <sequence>
                        <element name="customer_id" type="xs:int" />
                    </sequence>
                </complexType>
            </element>
                
            <element name="CustomerResponse">
                <complexType>
                    <sequence>
                        <element name="Request" minOccurs="1" />
                        <element name="Response" minOccurs="1" type="tns:CustomerResponseData" />
                    </sequence>
                </complexType>
            </element>
                        
            <element name="CustomRequest">
                <complexType>
                </complexType>
            </element>
                
            <element name="CustomResponse">
                <complexType>
                    <sequence>
                        <element name="Request" minOccurs="1" />
                        <element name="Response" minOccurs="1" />
                    </sequence>
                </complexType>
            </element>
                        
        </xs:schema>
    </types>
    
    
    <!-- Сообщения процедуры getCustomer -->
    <message name="getCustomerRequest">
        <part name="Request" element="tns:CustomerRequest" />
    </message>
    <message name="getCustomerResponse">
        <part name="Response" element="tns:CustomerResponse" />
    </message>
    
    <!-- Сообщения общие -->
    <message name="getCustomRequest">
        <part name="Request" element="tns:CustomRequest" />
    </message>
    <message name="getCustomResponse">
        <part name="Response" element="tns:CustomResponse" />
    </message>
    
    
    <!-- Привязка процедуры к сообщениям -->
    <portType name="LTKServicePortType">
        <operation name="getCustomer">
            <input message="tns:getCustomerRequest" />
            <output message="tns:getCustomerResponse" />
        </operation>
        <operation name="getCustomers">
            <input message="tns:getCustomRequest" />
            <output message="tns:getCustomResponse" />
        </operation>
    </portType>
    
    
    <!-- Формат процедур веб-сервиса -->
    <binding name="LTKServiceBinding" type="tns:LTKServicePortType">
        <soap:binding style="rpc" transport="http://schemas.xmlsoap.org/soap/http" />
        <operation name="getCustomer">
            <soap:operation soapAction="" />
            <input>
                <soap:body use="literal" />
            </input>
            <output>
                <soap:body use="literal" />
            </output>
        </operation>
        <operation name="getCustomers">
            <soap:operation soapAction="" />
            <input>
                <soap:body use="literal" />
            </input>
            <output>
                <soap:body use="literal" />
            </output>
        </operation>
    </binding>
    
    
    <!-- Определение сервиса -->
    <service name="LTKService">
        <port name="LTKServicePort" binding="tns:LTKServiceBinding">
            <soap:address location="<?php echo HTTP_LTK; ?>action.php" />
        </port>
    </service>
</definitions>