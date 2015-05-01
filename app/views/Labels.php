<?php

class Labels {
  private static $language;
  private static $reader;

  /**
   * @param string $labelId
   * @return string
   */
  public static function get($labelId)
  {
    $value = $labelId;
    self::$reader->open(app_path('views/labels.xml'));

    while( self::$reader->read() )
    {
      if ( self::$reader->nodeType == XMLReader::ELEMENT && self::$reader->name == 'label' )
      {
        $node = simplexml_load_string(self::$reader->readOuterXML());

        if ( self::$reader->getAttribute('id') == $labelId )
        {
          $value = $node->{self::$language};
          break;
        }
      }
    }

    self::$reader->close();
    return $value;
  }

  public static function initialize()
  {
    self::$language = Config::get('language');
    self::$reader = new XMLReader;
  }
}
