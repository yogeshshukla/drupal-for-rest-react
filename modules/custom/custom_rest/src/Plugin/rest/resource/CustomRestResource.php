<?php

namespace Drupal\custom_rest\Plugin\rest\resource;

use Drupal\Core\Session\AccountProxyInterface;
use Drupal\rest\ModifiedResourceResponse;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Drupal\node\Entity\Node;
use Drupal\file\Entity\File;

/**
 * Provides a resource to get view modes by entity and bundle.
 *
 * @RestResource(
 *   id = "custom_rest_resource",
 *   label = @Translation("Custom rest resource"),
 *   uri_paths = {
 *     "create" = "/api/custom"
 *   }
 * )
 */
class CustomRestResource extends ResourceBase {

	/**
	 * A current user instance.
	 *
	 * @var \Drupal\Core\Session\AccountProxyInterface
	 */
	protected $currentUser;

	/**
	 * Constructs a new CustomRestResource object.
	 *
	 * @param array                                      $configuration
	 *   A configuration array containing information about the plugin instance.
	 * @param string                                     $plugin_id
	 *   The plugin_id for the plugin instance.
	 * @param mixed                                      $plugin_definition
	 *   The plugin implementation definition.
	 * @param array                                      $serializer_formats
	 *   The available serialization formats.
	 * @param \Psr\Log\LoggerInterface                   $logger
	 *   A logger instance.
	 * @param \Drupal\Core\Session\AccountProxyInterface $current_user
	 *   A current user instance.
	 */
	public function __construct(
		array $configuration,
		$plugin_id,
		$plugin_definition,
		array $serializer_formats,
		LoggerInterface $logger,
		AccountProxyInterface $current_user ) {
		parent::__construct( $configuration, $plugin_id, $plugin_definition, $serializer_formats, $logger );

		$this->currentUser = $current_user;
	}

	/**
	 * {@inheritdoc}
	 */
	public static function create( ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition ) {
		return new static(
			$configuration,
			$plugin_id,
			$plugin_definition,
			$container->getParameter( 'serializer.formats' ),
			$container->get( 'logger.factory' )->get( 'custom_rest' ),
			$container->get( 'current_user' )
		);
	}

	/**
	 * Responds to POST requests.
	 *
	 * @param string $payload
	 *
	 * @return \Drupal\rest\ModifiedResourceResponse
	 *   The HTTP response object.
	 *
	 * @throws \Symfony\Component\HttpKernel\Exception\HttpException
	 *   Throws exception expected.
	 */
	public function post( $payload ) {
		return new ModifiedResourceResponse( $payload, 200 );
		
				// Get base64_encode of file data and explode to get data.
		
				// Decode the data.
			$decodedImgString = base64_decode( $payload['field_image'][0]['value'] );
			// Get the file name.
			$file        = file_save_data( $decodedImgString, 'public://' . 'demo_file.jpg', FILE_EXISTS_RENAME );
			$file_result = [
				'target_id' => $file->id(),
				'title'     => 'tile',
			];
			return new ModifiedResourceResponse( $file_result, 200 );

		print_r( $encodedImgString );
		exit;
		// Decode the data.
		$decodedImgString = base64_decode( $encodedImgString );
		// Save the file using base_decode() data.
		$file        = file_save_data( $decodedImgString, 'public://' . $file_data['title'], FILE_EXISTS_RENAME );
		$file_result = [
			'target_id' => $file->id(),
			'title'     => $file_data['title'],
		];

		return new ModifiedResourceResponse( $payload->field_image, 200 );

		// Save Image in local from remote data.
		$data = file_get_contents( 'path/data-folder/sample.png' );
		$file = file_save_data( $data, 'public://sample.png', FILE_EXISTS_REPLACE );

		// Create node object and save it.
		$node = Node::create(
			[
				'type'        => 'article',
				'title'       => 'Sample Node',
				'field_image' => [
					'target_id' => $file->id(),
					'alt'       => 'Sample',
					'title'     => 'Sample File',
				],
			]
		);
		$node->save();

		// You must to implement the logic of your REST Resource here.
		// Use current user after pass authentication to validate access.
		if ( ! $this->currentUser->hasPermission( 'access content' ) ) {
			throw new AccessDeniedHttpException();
		}

		return new ModifiedResourceResponse( $node, 200 );
	}

	/**
	 * Responds to PUT requests.
	 *
	 * @param string $payload
	 *
	 * @return \Drupal\rest\ModifiedResourceResponse
	 *   The HTTP response object.
	 *
	 * @throws \Symfony\Component\HttpKernel\Exception\HttpException
	 *   Throws exception expected.
	 */
	public function put( $payload ) {

		// You must to implement the logic of your REST Resource here.
		// Use current user after pass authentication to validate access.
		if ( ! $this->currentUser->hasPermission( 'access content' ) ) {
			throw new AccessDeniedHttpException();
		}

		return new ModifiedResourceResponse( $payload, 201 );
	}


}
