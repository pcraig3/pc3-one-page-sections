<?php

/**
 * Class Lib_PC3CSSFileEditor
 *
 * @TODO: Commenting
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
