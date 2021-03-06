import java.io.BufferedWriter;
import java.io.FileWriter;
import java.io.IOException;

import edu.uci.ics.crawler4j.crawler.CrawlConfig;
import edu.uci.ics.crawler4j.crawler.CrawlController;
import edu.uci.ics.crawler4j.fetcher.PageFetcher;
import edu.uci.ics.crawler4j.robotstxt.RobotstxtConfig;
import edu.uci.ics.crawler4j.robotstxt.RobotstxtServer;


public class MyCrawlController {
	
		
	public static void main(String args[]) throws Exception {
		String crawlStorageFolder = "/data/crawl";
		int numberOfCrawlers = 7;
		int maxDepthOfCrawling = 5;
		int maxPagesToFetch = 5000;
		//int politenessDelay = 300;
		String userAgentString = "CSCI 572 Sample Crawl";
		String seed = "http://gould.usc.edu/";
		
		try{
			BufferedWriter bw = new BufferedWriter(new FileWriter("fetch.csv"));
			bw.write("Attempted URLs to Fetch,Status Code\n");
			bw.close();
			bw = new BufferedWriter(new FileWriter("urls.csv"));
			bw.write("Processed URLs,Indicator\n");
			bw.close();
			bw = new BufferedWriter(new FileWriter("visit.csv"));
			bw.write("Successfully Downloaded URL,Size of Downloaded File,Number of Outgoing Links,Content Type\n");
			bw.close();
			bw = new BufferedWriter(new FileWriter("pagerankdata.csv"));
			bw.write("Page,Outgoing Links\n");
			bw.close();
			bw = new BufferedWriter(new FileWriter("docid.csv"));
			bw.write("DocID,DocURL\n");
			bw.close();
		} catch (IOException ioe){
			ioe.printStackTrace();
		}
		
		CrawlConfig config = new CrawlConfig();
		config.setCrawlStorageFolder(crawlStorageFolder);
		config.setMaxDepthOfCrawling(maxDepthOfCrawling);
		config.setMaxPagesToFetch(maxPagesToFetch);
		//config.setPolitenessDelay(politenessDelay);
		config.setUserAgentString(userAgentString);
		config.setIncludeBinaryContentInCrawling(true);
		config.setProcessBinaryContentInCrawling(true);
		config.setMaxDownloadSize(10485760);
		/*
		* Instantiate the controller for this crawl.
		*/
		PageFetcher pageFetcher = new PageFetcher(config);
		RobotstxtConfig robotstxtConfig = new RobotstxtConfig();
		RobotstxtServer robotstxtServer = new RobotstxtServer(robotstxtConfig, pageFetcher);
		CrawlController controller = new CrawlController(config, pageFetcher, robotstxtServer);
		
		/*
		* For each crawl, you need to add some seed urls. These are the first
		* URLs that are fetched and then the crawler starts following links
		* which are found in these pages
		*/
		controller.addSeed(seed);
		/*
		* Start the crawl. This is a blocking operation, meaning that your code
		7
		* will reach the line after this only when crawling is finished.
		*/
		controller.start(MyCrawler.class, numberOfCrawlers);
	}
}
