<?php

include 'parameters.php';

class CreateJDF
{

	public $JDFInitialize;

    public $ResourcePool;

    public $ResourceLinkPool;

	public $FileDirectory;

	public function __construct($DescriptiveName, $Types)
	{
		global $UltimateFileDestination;
		$this->FileDirectory = $UltimateFileDestination;

		// These are used to generate the initial XML field attributes
        $XMLEncoding = '<?xml version="1.0" encoding="UTF-8"?>';
		$JDFType = 'Combined';
		$XMLNS = 'http://www.CIP4.org/JDFSchema_1_1';
		$Id = 'rootNodeId';
		$Status = 'Waiting';
		$JobPartId = '000.cdp.797';

        // Check for acceptable types
        $AcceptableTypes = array(
            'Binding', 'Cutting', 'DigitalPrinting', 'FinalImaging', 'FinalRIPing', 'Folding', 'Newsprinting', 'PostPress', 'PrePress', 'Printing', 'ProofImaging', 'ProofRIPing', 'PublishingPreparation', 'RIPing', 'WebPrinting'
        );

        if(!in_array($Types, $AcceptableTypes)) throw new Exception("[$Types] is not an acceptable JDF type.");

		$Version = '1.3';

		// Initialize the JDF
		$JDFStart = $XMLEncoding.'<JDF Type="'. $JDFType .'" xmlns="'. $XMLNS .'" ID="'. $Id .'" Status="'. $Status .'" JobPartID="'. $JobPartId .'" Version="'. $Version .'" Types="'. $Types .'" DescriptiveName="'. $DescriptiveName .'"></JDF>';

		$this->JDFInitialize = new SimpleXMLElement($JDFStart, LIBXML_NOEMPTYTAG);

        // Add some information about where this JDF came from
        $AuditPool = $this->JDFInitialize->addChild('AuditPool');
        $Comments = $AuditPool->addChild("Created");
        $Comments->addAttribute("AgentName", "PHP-JDF-LIbrary");
        $Comments->addAttribute("TimeStamp", date("Y-m-d H:i:s"));

        // Add children that we will need regardless
        $this->ResourcePool = $this->JDFInitialize->addChild("ResourcePool");
        $this->ResourceLinkPool = $this->JDFInitialize->addChild("ResourceLinkPool");

	}

    public function setComment($comment)
    {
        $Comments = $this->JDFInitialize->addChild('Comment', $comment);
        $Comments->addAttribute("Name", "GeneralComments");
    }

    public function setMedia($SubstrateName)
    {
        $Media = $this->ResourcePool->addChild("Media");
        $Media->addAttribute("Class", "Consumable");
        $Media->addAttribute("ID", "M001");
        $Media->addAttribute("Status", "Available");
        $Media->addAttribute("DescriptiveName", $SubstrateName);
    }

	public function setFile($LocalFile)
	{
		$RunList = $this->ResourcePool->addChild("RunList");
		$LayoutElement = $RunList->addChild("LayoutElement");
		$FileSpec = $LayoutElement->addChild("FileSpec");

		// Get MIME type of file passed through
		if(!file_exists($LocalFile)) throw new Exception("[$LocalFile] cannot be found and therefore the MIME type cannot be determined.");
		$FileInfo = finfo_open(FILEINFO_MIME_TYPE);
		$MIMEType = finfo_file($FileInfo, $LocalFile);
		finfo_close($FileInfo);
		$FileSpec->addAttribute("MimeType", $MIMEType);

		$URL = $this->FileDirectory.basename($LocalFile);
		$FileSpec->addAttribute("URL", $URL);
		// Return the final file destination that the print device will be looking for
		return $URL;
	}
	public function getXML()
	{
        $ReturnXML = $this->JDFInitialize->asXML();
        return $ReturnXML;
	}


}