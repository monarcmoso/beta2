				->addProductFilter($product);
			$collection->getSelect()->order('related_product.position', 'ASC');
			$this->setData('{{entity}}_collection', $collection);
		}
		return $this->getData('{{entity}}_collection');
	}
}