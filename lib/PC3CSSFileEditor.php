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
	 * @since      0.8.0
	 */
	function __construct( $_sFilename ) {

		if( ! file_exists( $_sFilename ) )
			return new WP_Error( 'CSS file not found', printf( __( 'Custom CSS file (%1$s) was not found.', 'one-page-sections' ), $_sFilename ) );

		$this->sFilename = $_sFilename;
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
	 * @since      0.8.0
	 */
	public function writeToCustomCSSFile( $sContent ) {

		if( strlen( $sContent ) < 1 )
			return 0;

		$oFileHandler = $this->getSplFileObject( 'w' );

		//Returns the number of bytes written, or NULL on error.
		$iWritten = $oFileHandler->fwrite( $sContent );

		return $iWritten;
	}

	/**
	 * @since      0.8.0
	 */
	private function getSplFileObject( $prefix ) {

		return new SplFileObject( $this->sFilename, $prefix );
	}
}
