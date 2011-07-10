<?php
/**
 * Class representing a node / element in a graph.
 *
 * @category  DocBlox
 * @package   GraphViz
 * @author    Mike van Riel <mike.vanriel@naenius.com>
 * @copyright Copyright (c) 2011-201 Mike van Riel / Naenius (http://www.naenius.com)
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 */
class DocBlox_GraphViz_Node {

  /** @var string Name for this node */
  protected $name = '';

  /** @var DocBlox_GraphViz_Attribute[] List of attributes for this node */
  protected $attributes = array();

  /**
   * Creates a new node with name and optional label.
   *
   * @param string      $name
   * @param string|null $label
   */
  function __construct($name, $label = null)
  {
    $this->setName($name);
    if ($label !== null)
    {
      $this->setLabel($label);
    }
  }

  /**
   * Factory method used to assist with fluent interface handling.
   *
   * See the examples for more details.
   *
   * @param string      $name
   * @param string|null $label
   *
   * @return DocBlox_GraphViz_Node
   */
  public static function create($name, $label = null)
  {
    return new self($name, $label);
  }

  /**
   * Sets the name for this node.
   *
   * Not to confuse with the label.
   *
   * @param string $name
   *
   * @return DocBlox_GraphViz_Node
   */
  public function setName($name)
  {
    $this->name = $name;
    return $this;
  }

  /**
   * Returns the name for this node.
   *
   * @return string
   */
  public function getName()
  {
    return $this->name;
  }

  /**
   * Magic method to provide a getter/setter to add attributes on the Node.
   *
   * Using this method we make sure that we support any attribute without too much hassle.
   * If the name for this method does not start with get or set we return null.
   *
   * Set methods return this graph (fluent interface) whilst get methods return the attribute value.
   *
   * @param string  $name
   * @param mixed[] $arguments
   *
   * @return DocBlox_GraphViz_Attribute[]|DocBlox_GraphViz_Node|null
   */
  function __call($name, $arguments)
  {
    $key = strtolower(substr($name, 3));
    if (strtolower(substr($name, 0, 3)) == 'set')
    {
      $this->attributes[$key] = new DocBlox_GraphViz_Attribute($key, $arguments[0]);
      return $this;
    }
    if (strtolower(substr($name, 0, 3)) == 'get')
    {
      return $this->attributes[$key];
    }

    return null;
  }

  /**
   * Returns the node definition as is requested by GraphViz.
   *
   * @return string
   */
  public function __toString()
  {
    $attributes = array();
    foreach ($this->attributes as $value)
    {
      $attributes[] = (string)$value;
    }
    $attributes = implode("\n", $attributes);

    return <<<DOT
{$this->getName()} [
$attributes
]
DOT;
  }
}
