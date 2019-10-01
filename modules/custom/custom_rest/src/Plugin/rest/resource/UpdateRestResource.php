<?php

namespace Drupal\custom_rest\Plugin\rest\resource;

use Drupal\Core\Session\AccountProxyInterface;
use Drupal\rest\ModifiedResourceResponse;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Provides a resource to get view modes by entity and bundle.
 *
 * @RestResource(
 *   id = "update_rest_resource",
 *   label = @Translation("Update rest resource"),
 *   uri_paths = {
 *     "canonical" = "/api/custom/update"
 *   }
 * )
 */
class UpdateRestResource extends ResourceBase {

	/**
	 * A current user instance.
	 *
	 * @var \Drupal\Core\Session\AccountProxyInterface
	 */
	protected $currentUser;

	/**
	 * Constructs a new UpdateRestResource object.
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
