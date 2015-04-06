<?php

/**
 * Class exists to overwrite a custom CSS file applied on the 'sections' page of our site.
 * Pretty sure that the way it works right now is the bare minimum and we need to improve this.
 *
 * @since      0.8.0
 *
 * @package    One_Page_Sections
 * @subpackage One_Page_Sections/lib
 */
class Lib_PC3CSSFileEditor {

	/**
	 * @since      0.8.0
	 */
	private $sFilename;

    /**
     * @since      0.9.1
     */
    private $sDefaultContent;

	/**
	 * @since      0.9.1
	 */
	function __construct( $_sFilename, $_sDefaultContent ) {

		if( ! file_exists( $_sFilename ) )
			return new WP_Error( 'CSS file not found', printf( __( 'Custom CSS file (%1$s) was not found.', 'one-page-sections' ), $_sFilename ) );

		$this->sFilename = $_sFilename;
        $this->sDefaultContent = $_sDefaultContent;
	}

    /**
     * Returns the default content for our custom CSS file.
     *
     * @since      0.9.1
     *
     * @return string   default content string
     */
    public function getDefaultContent() {

        return $this->sDefaultContent;
    }

	/**
	 * @since      0.8.0
	 */
	public function readContentOfCustomCSSFile() {

		$oFileHandler = $this->getSplFileObject( 'r' );

		$sContent = '';

		while (! $oFileHandler->eof() ) {
			$sContent .= $oFileHandler->current();
			$oFileHandler->next();
		}

		return $sContent;
	}

	/**
	 * @since      0.9.1
	 */
	public function writeToCustomCSSFile( $sContent ) {

		if( strlen( $sContent ) < 1 )
			$sContent = $this->sDefaultContent;

		$oFileHandler = $this->getSplFileObject( 'w' );

		//Returns the number of bytes written, or NULL on error.
		return $oFileHandler->fwrite( $sContent );
	}

	/**
	 * @since      0.8.0
	 */
	private function getSplFileObject( $prefix ) {

		return new SplFileObject( $this->sFilename, $prefix );
	}
}
