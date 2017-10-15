<?php

import('plugins.generic.externalFeed.simplepie.SimplePie');

class Arxiv {
	var $_arxivId;

	var $_feed;

	/**
	 * Constructor.
	 */
	function __construct($arxivId) {
		$arxivId = preg_replace("/^arxiv:\s*/i", "", trim($arxivId));
		$this->_arxivId = $arxivId;
		if (!preg_match("/^\d{4}\.\d+(v\d+)?$/", $arxivId)) {
			$this->_feed = null;
			return;
		}
		$url = "http://export.arxiv.org/api/query?id_list=" . $arxivId;
		$feed = new SimplePie($url);
		$result = $feed->get_item();
		if (!$result || preg_match("/error/", $result->get_id()) || trim($result->get_title()) === "") {
			$this->_feed = null;
			return;
		}
		$this->_feed = $result;
	}

	function getArxivId() {
		return $this->_arxivId;
	}

	function isValid() {
		return $this->_feed !== null;
	}

	function getTitle() {
		if ($this->isValid())
			return $this->_feed->get_title();
	}

	function getAbstract() {
		if ($this->isValid())
			return $this->_feed->get_content();
	}

	function getAuthors() {
		if ($this->isValid())
			return $this->_feed->get_authors();
	}

	function getPDFUrl() {
		if ($this->isValid()) {
			foreach($this->_feed->get_links('related') as $url) {
				if (strpos($url, "pdf") !== false) return $url;
			}
		}
	}
}

?>
