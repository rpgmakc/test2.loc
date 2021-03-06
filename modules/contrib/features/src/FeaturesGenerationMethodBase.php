<?php

namespace Drupal\features;

use Drupal\Component\Serialization\Yaml;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Base class for package assignment methods.
 */
abstract class FeaturesGenerationMethodBase implements FeaturesGenerationMethodInterface {
  use StringTranslationTrait;

  /**
   * The features manager.
   *
   * @var \Drupal\features\FeaturesManagerInterface
   */
  protected $featuresManager;

  /**
   * The features assigner.
   *
   * @var \Drupal\features\FeaturesAssignerInterface
   */
  protected $assigner;

  /**
   * {@inheritdoc}
   */
  public function setFeaturesManager(FeaturesManagerInterface $features_manager) {
    $this->featuresManager = $features_manager;
  }

  /**
   * {@inheritdoc}
   */
  public function setAssigner(FeaturesAssignerInterface $assigner) {
    $this->assigner = $assigner;
  }

  /**
   * {@inheritdoc}
   */
  public function exportFormSubmit(array &$form, FormStateInterface $form_state) {

  }

  /**
   * Merges an info file into a package's info file.
   *
   * @param string $package_info
   *   The Yaml encoded package info.
   * @param string $info_file_uri
   *   The info file's URI.
   */
  protected function mergeInfoFile($package_info, $info_file_uri) {
    $package_info = Yaml::decode($package_info);
    // \Drupal\Core\Extension\InfoParser::parse() makes changes we don't want
    // here such as adding a core_incompatible key. Instead parse the file
    // directly.
    $existing_info = Yaml::decode(file_get_contents($info_file_uri));
    return Yaml::encode($this->featuresManager->mergeInfoArray($existing_info, $package_info));
  }

  /**
   * {@inheritdoc}
   */
  public function prepare(array &$packages = [], FeaturesBundleInterface $bundle = NULL) {
    // If no packages were specified, get all packages.
    if (empty($packages)) {
      $packages = $this->featuresManager->getPackages();
    }

    // If any packages exist, read in their files.
    $existing_packages = $this->featuresManager->listPackageDirectories(array_keys($packages), $bundle);

    foreach ($packages as &$package) {
      list($full_name, $path) = $this->featuresManager->getExportInfo($package, $bundle);
      if (empty($package->getDirectory())) {
        $package->setDirectory($path);
      }

      // If this is the profile, its directory is already assigned.
      if (!isset($bundle) || !$bundle->isProfilePackage($package->getMachineName())) {
        $current_path = $package->getDirectory();
        if (strpos($current_path, $full_name) < strlen($current_path) - strlen($full_name)) {
          // Only append package name if it isn't already there.
          $package->setDirectory($package->getDirectory() . '/' . $full_name);
        }
      }

      $this->preparePackage($package, $existing_packages, $bundle);
    }
    // Clean up the $package pass by reference.
    unset($package);
  }

  /**
   * Performs any required changes on a package prior to generation.
   *
   * @param \Drupal\features\Package $package
   *   The package to be prepared.
   * @param array $existing_packages
   *   An array of existing packages with machine names as keys and paths as
   *   values.
   * @param \Drupal\features\FeaturesBundleInterface $bundle
   *   Optional bundle used for export.
   */
  abstract protected function preparePackage(Package $package, array $existing_packages, FeaturesBundleInterface $bundle = NULL);

}
