<?php

namespace Drupal\field_test\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityFieldManagerInterface;

/**
 * Class DefaultController.
 */
class DefaultController extends ControllerBase {

  /**
   * Drupal\Core\Entity\EntityFieldManagerInterface definition.
   *
   * @var \Drupal\Core\Entity\EntityFieldManagerInterface
   */
  protected $entityFieldManager;

  /**
   * Constructs a new DefaultController object.
   */
  public function __construct(EntityFieldManagerInterface $entity_field_manager) {
    $this->entityFieldManager = $entity_field_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_field.manager')
    );
  }

  /**
   * Fieldtest.
   *
   * @return string
   *   Return Hello string.
   */
  public function fieldTest() {
    $missing_fields = '';
    foreach ($this->entityFieldManager->getFieldMap() as $entity_type_id => $entity_field_map) {
      foreach ($entity_field_map as $field_name => $field_info) {

        foreach ($field_info['bundles'] as $bundle) {
          $field_definitions = $this->entityFieldManager->getFieldDefinitions($entity_type_id, $bundle);
          if (empty($field_definitions[$field_name])) {
            $missing_fields .= "<p>$entity_type_id - $bundle: $field_name</p>";
          }
        }
      }
    }

    if ($missing_fields) {
      $missing_fields = "Missing fields: $missing_fields";
    }
    else {
      $missing_fields = "No missing fields";
    }
    return [
      '#type' => 'markup',
      '#markup' => $missing_fields,
    ];
  }

}
