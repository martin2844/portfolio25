---
title: "Slack Cell Architecture Explained"
publishDate: "2023-08-26"
slug: "slack-cell-architecture"
excerpt: "How Slack scaled its architecture with cells to keep performance promises. A summary of the problem, the idea and what makes it interesting."
readingTime: 8
tags: ["server"]
---

The architecture of slack cells, I found this article very interesting because it talks about a fairly complex problem that slack had. It all starts from the promise they have that in a year Slack cannot be down for more than an hour.

Some terms that you consider important will be in bold and colored, which means that they will be defined in the glossary at the end of this text to have their definition at hand.

> First things first, the source, and the original article.  
> Original article:  
> <https://slack.engineering/slacks-migration-to-a-cellular-architecture/>

## Slack architecture: the beginning

The article begins by explaining that Slack has spent the last year and a half migrating its architecture and the reason for this migration is clear. The architectures alone don't matter much in my opinion, it is truly in the use cases where you really learn and see the pros and cons of each pattern or design.

So Slack decides to change its architecture from Monolith to cellular, which by the way, monolith is one of the easiest architectures to get started. It's all concentrated in one place, and it was used by Slack until 18 months ago (when Slack already has millions of users). The first thing I deduce is that for almost anything that has to start from scratch, monoliths are a good option. Always, as in the case of Slack, it can be changed later, even if it takes extra effort. This is what many say, it is better to go to the market quickly and quickly, than to take a long time and launch when another product may beat you to it.

Let's get to the interesting stuff, the architecture, the systems, the programming and all that stuff that's fascinating.

#### Why the switch to cells, what is the benefit?

They mainly highlight that this design has become quite popular for online services, since it increases redundancy and limits the scope of critical errors in the system. (IONOS, 2022) To summarize, if our system is divided into cells, which handle redundant information, if one cell fails, in principle the system would not be affected since another cell can work for the one that fails.

#### How was the decision made to make this investment?

Slack had all of its infrastructure in one geographic area (us-east-1). On June 30, 2021, there was a network problem in the area, and that basically resulted in a degraded service for slack users. If you use slack, you have surely experienced this type of thing in recent years, images that are not visible, messages that arrive late, or channels that do not load. After trying to lift the error, and fix the network, it failed again causing problems again. This forced Slack engineers to ask themselves, how could it be that having a multi-regional system based on “**edges**” the system fails due to the fall of a geographic area?

Answering that is quite complex, it involves understanding your entire system, how information is requested from the front-end, and from there how it is finally obtained and returned. The article mentions that *"it turns out that detecting failures in distributed systems is a difficult problem. A single Slack API request from a user (for example, uploading messages to a channel) can trigger hundreds of RPCs to the service backends, each of which must complete to return a correct response to the user. Our service frontends continually try to detect and exclude failed backends, but we have to log some failures before we can exclude a failed server."* (Bethea, 2023)

In short, what happened with this specific failure was that all the services within the affected **AZ (Availability Zone)** took the services within the same zone as functional, but the external ones, in other AZs, as down. So it is logical that the system would degrade, without going into details, if A and B are inside the us-east AZ, but C for some reason is in the us-west AZ, A and B communicate well with each other, but they took C as down and this would cause problems in general. Different components had varied perspectives on the availability of the system and Slack called this a gray failure, meaning it is not that the service as such does not work, but that it is degraded.

### The solution: AZs as cells.

Instead of immediately trying to fix these gray faults, which can be difficult to diagnose and fix, Slack began deploying AZs as cells, allowing them to create a button to clear cells with gray faults. How is the emptying? Gradually, avoiding errors that users may see, and redirecting traffic from AZs with problems to AZs that work well. In this way, cells can be turned off until they work again. (Bethea, 2023)

#### The concept of Silos

Silo refers to the graphic form given to the cell, each AZ is a cell, and each cell contains all the services, with which it can redirect traffic from cells that do not work to others that do, and little by little the one that does not work is “turned off”. All communications between services are within each cell, there is no communication between AZs which allows this operation.

![Silos](/public/posts/image3.webp "Silos")  
(Bethea, 2023)

### Implementation

For this, I think it deserves another article because the explanation is very deep and would take too long. But in summary, Slack previously migrated the way they distribute the load of their **websockets** **(load-balancers)** from **HAProxy** to **Envoy**, which allowed them to meet their cell migration goals. The main objectives were:

1. Propagation through the control plane is on the order of seconds; Envoy freight distributors will apply new weights immediately.
2. Drains are smooth; no queries to a drained AZ will be abandoned by the load balancing layer.
3. The weights applied offer gradual drainages with a granularity of 1%.
4. The edge load distributors are located in completely different regions, and the control plane is regionally replicated and resilient against the failure of any individual AZ. (Betheas, 2023)

In short, the implementation involves using a button (the drain button) to send a signal to Rotor, its in-house system to control the Envoy configuration, and it would move the load from one AZ to another. In this case, the load would be moved from us-east to anyone else that has good service.

## Glossary

**Redundancy:** The duplication of critical components or functions of a system to increase its reliability. Redundancy is often used in computer systems and networks to ensure that if one component fails, a backup component can take over, preventing system downtime.

**AZ (Availability Zones):** A distinct, independent data center within a specific geographic region in a cloud provider's infrastructure. Each Availability Zone is designed to be isolated from the failures of other Availability Zones and typically have independent power, cooling, and networking.

**Edges (networks):** Refers to the decentralized edge of the network infrastructure closest to the data source, which is often the end user. This decentralized frontier has become crucial in the design of modern architectures, especially with the increasing demand for real-time processing and the need to reduce latency. In terms of cloud systems, edge can address multiple concepts such as edge networks which are decentralized networks that connect edge devices to the core of the cloud infrastructure. These networks are essential to ensure fast and secure communication between edge devices and the centralized data center.

**RPCs (Remote Procedure Calls)**: A protocol that allows a program to cause the execution of a procedure or function in another address space (commonly on another computer on a shared network). It allows communication between processes, such as between a client and a server.

**Load-Balancer:** A load-balancer is a tool or device that efficiently distributes network traffic or incoming requests across multiple servers or available resources. Its main objective is to optimize the use of resources, maximize performance, minimize response time and avoid overloading a single resource. Load distributors can be implemented in hardware or software and can base their distribution on different criteria, such as current server load, capacity, response speed, or even business-specific algorithms. In addition, they improve the availability and reliability of applications, since they can redistribute traffic in the event of a server failure. In modern cloud and data center architectures, the use of load spreaders is essential to ensure an efficient and stable user experience.

**Websocket**: A communication protocol that provides full-duplex communication channels over a single TCP connection.

**HaProxy:** An open source proxy server that offers high availability, load sharing and proxy capability for TCP and HTTP based applications. It is used to improve the performance and reliability of servers and services.

**Envoy**: An edge proxy and service designed for microservices-based applications. It is used to manage network traffic between microservices and to address issues related to service discovery, load distribution, authentication, and others.

## References

IONOS. (2022, September 26). What is redundancy in computing? IONOS Digital Guide. [Resource](https://www.ionos.es/digitalguide/servidores/security/redundancia/#:~:text=Los%20sistemas%20son%20redundantes%20si,protege%20contra%20p%C3%A9rdidas%20y%20fallos. "Resource")

Bethea, C. (2023, August 22). Slack’s migration to a cellular architecture. SlackEngineering. <https://slack.engineering/slacks-migration-to-a-cellular-architecture/>

---

> Original article in Spanish: [Arquitectura de Celulas de Slack](https://codigomate.com/arquitectura-de-celulas-de-slack/)